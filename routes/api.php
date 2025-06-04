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
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);

// Autenticación
Route::post('/register', [RegisterUserApiController::class, 'register']);
Route::post('/login', [LoginUserApiController::class, 'login']);
Route::post('/logout', [LoginUserApiController::class, 'logout'])->middleware('auth:sanctum');

// Rutas públicas
Route::apiResource('clubs', ClubController::class);
Route::apiResource('players', PlayerController::class);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // CRUD básico
    Route::apiResource('users', UserApiController::class);
    Route::apiResource('lineups', LineupController::class);

    // Ligas
    Route::apiResource('leagues', LeagueController::class);
    Route::post('/leagues/{league}/join', [LeagueController::class, 'join']);
    Route::post('/leagues/{league}/assign-players', [LeagueController::class, 'assignPlayers']);
    Route::get('/leagues/{league}/users', [LeagueController::class, 'getLeagueUsers']);
    Route::get('/leagues/{league}/ranking', [LeagueController::class, 'getRanking']);
    Route::get('/leagues/{league}/info', [LeagueController::class, 'infoLiga']);
    Route::get('/my-leagues', [LeagueController::class, 'myLeagues']);
    Route::get('/leagues/user/check', [LeagueController::class, 'checkUserLeague']);
});

// API de fútbol
Route::prefix('football')->group(function () {
    Route::get('teams', [FootballApiController::class, 'getSegundaDivisionTeams']);
    Route::get('players', [FootballApiController::class, 'getSegundaDivisionPlayers']);
    Route::post('sync', [FootballApiController::class, 'syncTeamsAndPlayers']);
});

Route::post('/register', [AuthController::class, 'register']);
