<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RegisterUserApiController;
use App\Http\Controllers\API\LoginUserApiController;
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
