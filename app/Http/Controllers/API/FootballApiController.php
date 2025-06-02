<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FootballApiController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://v3.football.api-sports.io';
    private const SEGUNDA_DIVISION_ID = 141;

    public function __construct()
    {
        $this->apiKey = env('FOOTBALL_API_KEY');

        if (empty($this->apiKey)) {
            throw new \RuntimeException('API key no configurada');
        }
    }

    /**
     * Sincroniza equipos y jugadores de la Segunda División
     */
    public function syncTeamsAndPlayers()
    {
        Log::info('Iniciando sincronización de Segunda División');

        try {
            $currentYear = date('Y');
            $result = [
                'teams_synced' => 0,
                'players_synced' => 0,
                'errors' => []
            ];

            // 1. Obtener equipos
            $teams = $this->fetchTeams($currentYear);

            if (empty($teams)) {
                throw new \Exception('No se encontraron equipos en la API');
            }

            // 2. Procesar cada equipo
            foreach ($teams as $teamData) {
                try {
                    $club = $this->syncClub($teamData);
                    $result['teams_synced']++;

                    // 3. Obtener y sincronizar jugadores
                    $players = $this->fetchTeamPlayers($teamData['team']['id'], $currentYear);

                    foreach ($players as $playerData) {
                        try {
                            $this->syncPlayer($playerData, $club->id);
                            $result['players_synced']++;
                        } catch (\Exception $e) {
                            $result['errors'][] = "Jugador: " . $e->getMessage();
                        }
                    }
                } catch (\Exception $e) {
                    $result['errors'][] = "Equipo {$teamData['team']['name']}: " . $e->getMessage();
                }
            }

            Log::info('Sincronización completada', $result);
            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error en syncTeamsAndPlayers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene equipos de la API
     */
    public function fetchTeams($season)
    {
        try {
            $response = Http::withHeaders([
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => 'v3.football.api-sports.io'
            ])->timeout(30)->get($this->baseUrl.'/teams', [
                'league' => self::SEGUNDA_DIVISION_ID,
                'season' => $season
            ]);

            if ($response->failed()) {
                Log::error('API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);
                throw new \Exception("Error API: ".$response->status()." - ".$response->body());
            }

            $data = $response->json();

            if (empty($data['response'])) {
                Log::warning('API returned empty response', $data);
                throw new \Exception("La API no devolvió equipos. ¿La temporada está activa?");
            }

            return $data['response'];

        } catch (\Exception $e) {
            Log::error('fetchTeams Error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Sincroniza un club en la base de datos
     */
    private function syncClub($teamData)
    {
        $team = $teamData['team'];
        $venue = $teamData['venue'];

        return Club::updateOrCreate(
            ['api_id' => $team['id']],
            [
                'name_club' => $team['name'],
                'city' => $venue['city'] ?? 'Desconocida',
                'foundation' => is_numeric($team['founded'] ?? null) ? $team['founded'] : null,
                'image' => $team['logo'] ?? null,
                'venue_name' => $venue['name'] ?? null,
                'venue_capacity' => $venue['capacity'] ?? null
            ]
        );
    }

    /**
     * Obtiene jugadores de un equipo
     */
    private function fetchTeamPlayers($teamId, $season)
    {
        $response = Http::withHeaders([
            'x-rapidapi-key' => $this->apiKey,
            'x-rapidapi-host' => 'v3.football.api-sports.io'
        ])->get($this->baseUrl.'/players', [
            'league' => self::SEGUNDA_DIVISION_ID,
            'season' => $season,
            'team' => $teamId
        ]);

        if ($response->failed()) {
            throw new \Exception("API Error al obtener jugadores: " . $response->status());
        }

        $data = $response->json();
        return $data['response'] ?? [];
    }

    /**
     * Sincroniza un jugador en la base de datos
     */
    private function syncPlayer($playerData, $clubId)
    {
        $player = $playerData['player'];
        $stats = $playerData['statistics'][0] ?? [];

        return Player::updateOrCreate(
            ['api_id' => $player['id']],
            [
                'name_player' => $player['name'],
                'position' => $this->mapPosition($stats['games']['position'] ?? null),
                'market_value' => $this->calculateMarketValue($player, $stats),
                'club_id' => $clubId,
                'image' => $player['photo'] ?? null,
                'nationality' => $player['nationality'] ?? null,
                'birth_date' => isset($player['birth']['date']) ?
                    Carbon::parse($player['birth']['date']) : null,
                'height' => $player['height'] ?? null,
                'weight' => $player['weight'] ?? null,
                'injured' => $player['injured'] ?? false
            ]
        );
    }

    /**
     * Mapea posiciones de la API a nuestras categorías
     */
    private function mapPosition($apiPosition)
    {
        $positionMap = [
            'Goalkeeper' => ['Goalkeeper', 'Portero'],
            'Defender' => ['Defender', 'Centre-Back', 'Left-Back', 'Right-Back', 'Defensa'],
            'Midfielder' => ['Midfielder', 'Defensive Midfield', 'Attacking Midfield',
                'Left Midfield', 'Right Midfield', 'Centrocampista'],
            'Forward' => ['Forward', 'Centre-Forward', 'Left Wing',
                'Right Wing', 'Delantero', 'Attacker']
        ];

        foreach ($positionMap as $dbPosition => $apiPositions) {
            if (in_array($apiPosition, $apiPositions)) {
                return $dbPosition;
            }
        }

        return 'Midfielder';
    }

    /**
     * Calcula valor de mercado aproximado
     */
    private function calculateMarketValue($player, $stats)
    {
        // Si la API proporciona valor, usamos ese
        if (isset($player['value']) && $player['value'] > 0) {
            return $player['value'];
        }

        // Cálculo estimado basado en estadísticas
        $baseValue = 100000; // 100k € base

        if (!empty($stats)) {
            $minutes = $stats['games']['minutes'] ?? 0;
            $goals = $stats['goals']['total'] ?? 0;
            $assists = $stats['goals']['assists'] ?? 0;

            $baseValue += ($minutes * 10) + ($goals * 50000) + ($assists * 30000);
        }

        return round($baseValue, -3); // Redondeo a miles
    }

    /**
     * Endpoint para verificar estado
     */
    public function getSyncStatus()
    {
        return response()->json([
            'last_sync' => Player::max('updated_at'),
            'total_teams' => Club::count(),
            'total_players' => Player::count()
        ]);
    }
}
