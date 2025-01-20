<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::apiResource('/users', UserApiController::class);
Route::get('/users/{user}/ofertas', [UserApiController::class, 'ofertas']);
