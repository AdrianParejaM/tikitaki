<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Club;
use App\Models\Player;

class ImportFootballData extends Command
{
    protected $signature = 'import:football-data';
    protected $description = 'Importa datos reales desde API Football';

    public function handle()
    {
        $apiKey = env('FOOTBALL_API_KEY');
        $currentYear = date('Y');

        // 1. Obtener equipos
        $teamsResponse = Http::withHeaders([
            'x-rapidapi-key' => $apiKey,
            'x-rapidapi-host' => 'v3.football.api-sports.io'
        ])->get('https://v3.football.api-sports.io/teams', [
            'league' => 141,
            'season' => $currentYear
        ]);

        $teams = $teamsResponse->json()['response'];

        // 2. Procesar equipos
        foreach ($teams as $teamData) {
            $team = $teamData['team'];
            $venue = $teamData['venue'];

            $club = Club::updateOrCreate(
                ['api_id' => $team['id']],
                [
                    'name_club' => $team['name'],
                    'city' => $venue['city'] ?? 'Desconocida',
                    'foundation' => $team['founded'] ?? null,
                    'image' => $team['logo'] ?? null,
                    'venue_name' => $venue['name'] ?? null
                ]
            );

            // 3. Obtener jugadores del equipo
            $playersResponse = Http::withHeaders([
                'x-rapidapi-key' => $apiKey,
                'x-rapidapi-host' => 'v3.football.api-sports.io'
            ])->get('https://v3.football.api-sports.io/players', [
                'league' => 141,
                'season' => $currentYear,
                'team' => $team['id']
            ]);

            $players = $playersResponse->json()['response'];

            // 4. Procesar jugadores
            foreach ($players as $playerData) {
                $player = $playerData['player'];

                Player::updateOrCreate(
                    ['api_id' => $player['id']],
                    [
                        'name_player' => $player['name'],
                        'position' => $this->mapPosition($player['position'] ?? null),
                        'market_value' => $this->estimateMarketValue(),
                        'club_id' => $club->id,
                        'image' => $player['photo'] ?? null,
                        'nationality' => $player['nationality'] ?? null
                    ]
                );
            }
        }

        $this->info('Datos importados correctamente!');
        $this->line('Equipos: '.Club::count());
        $this->line('Jugadores: '.Player::count());
    }

    /**
     * Función simplificada para mapear posiciones.
     */
    private function mapPosition(?string $apiPosition): string
    {
        if (empty($apiPosition)) {
            return 'Midfielder'; // Valor por defecto
        }

        // Convertimos a minúsculas para simplificar la comparación
        $position = strtolower($apiPosition);

        if (str_contains($position, 'goalkeeper') || str_contains($position, 'portero')) {
            return 'Goalkeeper';
        }

        if (str_contains($position, 'defender') || str_contains($position, 'defensa')) {
            return 'Defender';
        }

        if (str_contains($position, 'forward') || str_contains($position, 'delantero')) {
            return 'Forward';
        }

        // Cualquier otra cosa es Midfielder
        return 'Midfielder';
    }

    /**
     * Genera un valor de mercado aleatorio entre 500.000€ y 50.000.000€
     */
    private function estimateMarketValue(): int
    {
        return rand(500000, 50000000);
    }
}
