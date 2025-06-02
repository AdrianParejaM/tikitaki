<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\FootballApiController;

class SyncFootballData extends Command
{
    protected $signature = 'football:sync';
    protected $description = 'Synchronize football data from API';

    public function handle()
    {
        $controller = new FootballApiController();
        $result = $controller->syncTeamsAndPlayers();

        $this->info($result->original['message']);
    }
}
