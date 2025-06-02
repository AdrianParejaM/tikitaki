<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Club;
use App\Models\Player;
use Carbon\Carbon;

class ImportRealFootballData extends Command
{
    protected $signature = 'import:real-football-data';
    protected $description = 'Importa datos REALES de Segunda División 2023';

    public function handle()
    {
        $this->info('Iniciando importación de datos reales...');

        $apiKey = env('FOOTBALL_API_KEY');
        if (!$apiKey) {
            $this->error('❌ No hay API KEY configurada en .env');
            return;
        }

        // Usamos temporada 2023 (compatible con plan gratuito)
        $season = 2023;
        $leagueId = 141;

        // 1. Obtener equipos
        $teams = $this->fetchTeams($apiKey, $leagueId, $season);
        if (empty($teams)) {
            $this->error('❌ No se encontraron equipos. Verifica tu plan API.');
            return;
        }

        // 2. Procesar cada equipo
        foreach ($teams as $teamData) {
            try {
                $club = $this->createOrUpdateClub($teamData);
                $this->info("Procesando: {$club->name_club}");

                // 3. Obtener y procesar jugadores
                $players = $this->fetchPlayers($apiKey, $teamData['team']['id'], $leagueId, $season);
                foreach ($players as $playerData) {
                    $this->createOrUpdatePlayer($playerData, $club->id);
                }
            } catch (\Exception $e) {
                $this->error("Error procesando equipo: ".$e->getMessage());
            }
        }

        $this->info('✅ Importación completada!');
        $this->line("Total clubs: ".Club::count());
        $this->line("Total players: ".Player::count());
    }

    private function fetchTeams($apiKey, $leagueId, $season)
    {
        $response = Http::withHeaders([
            'x-rapidapi-key' => $apiKey,
            'x-rapidapi-host' => 'v3.football.api-sports.io'
        ])->get('https://v3.football.api-sports.io/teams', [
            'league' => $leagueId,
            'season' => $season
        ]);

        return $response->json()['response'] ?? [];
    }

    private function createOrUpdateClub($teamData)
    {
        $team = $teamData['team'];
        $venue = $teamData['venue'];

        // Formatear fecha de fundación con valor por defecto
        $foundation = '1900-01-01'; // Valor por defecto

        if (!empty($team['founded'])) {
            if (is_numeric($team['founded']) && strlen($team['founded']) === 4) {
                $foundation = Carbon::create($team['founded'], 1, 1)->format('Y-m-d');
            } else {
                try {
                    $foundation = Carbon::parse($team['founded'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Mantiene el valor por defecto
                }
            }
        }

        return Club::updateOrCreate(
            ['api_id' => $team['id']],
            [
                'name_club' => $team['name'],
                'city' => $venue['city'] ?? 'Desconocida',
                'foundation' => $foundation,
                'image' => $team['logo'],
                'venue_name' => $venue['name'] ?? null
            ]
        );
    }

    private function fetchPlayers($apiKey, $teamId, $leagueId, $season)
    {
        $response = Http::withHeaders([
            'x-rapidapi-key' => $apiKey,
            'x-rapidapi-host' => 'v3.football.api-sports.io'
        ])->get('https://v3.football.api-sports.io/players', [
            'league' => $leagueId,
            'season' => $season,
            'team' => $teamId
        ]);

        return $response->json()['response'] ?? [];
    }

    private function createOrUpdatePlayer($playerData, $clubId)
    {
        $player = $playerData['player'];

        return Player::updateOrCreate(
            ['api_id' => $player['id']],
            [
                'name_player' => $player['name'],
                'position' => $this->mapPosition($player['position'] ?? null),
                'market_value' => rand(500000, 50000000),
                'club_id' => $clubId,
                'image' => $player['photo'] ?? null,
                'nationality' => $player['nationality'] ?? 'ESP'
            ]
        );
    }

    private function mapPosition($position)
    {
        $position = strtolower($position ?? '');

        if (str_contains($position, 'goalkeeper')) return 'Goalkeeper';
        if (str_contains($position, 'defender')) return 'Defender';
        if (str_contains($position, 'forward')) return 'Forward';

        return 'Midfielder';
    }
}
