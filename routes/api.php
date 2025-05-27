<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RegisterUserApiController;
use App\Http\Controllers\API\LoginUserApiController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\LineupController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\FootballApiController;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('/users', UserApiController::class);
});

Route::post('/register', [RegisterUserApiController::class, 'register']);
Route::post('/login', [LoginUserApiController::class, 'login']);
Route::post('/logout', [LoginUserApiController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('clubs', ClubController::class);
Route::apiResource('leagues', LeagueController::class);
Route::apiResource('lineups', LineupController::class);
Route::apiResource('players', PlayerController::class);

Route::middleware('auth:sanctum')->group(function () {
    // Rutas CRUD estándar
    Route::apiResource('leagues', LeagueController::class);

    // Rutas adicionales
    Route::post('/leagues/{league}/join', [LeagueController::class, 'join']);
    Route::post('/leagues/{league}/assign-players', [LeagueController::class, 'assignPlayers']);
});

// Rutas para la API de football.
Route::prefix('football')->group(function () {
    Route::get('teams', [FootballApiController::class, 'getSegundaDivisionTeams']);
    Route::get('players', [FootballApiController::class, 'getSegundaDivisionPlayers']);
    Route::post('sync', [FootballApiController::class, 'syncTeamsAndPlayers']);
});
/*
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/leagues', [LeagueController::class, 'createLeague']);
    Route::post('/leagues/{league}/lineups', [LeagueController::class, 'createLineup']);
});
*/
