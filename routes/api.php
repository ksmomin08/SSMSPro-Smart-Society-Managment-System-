<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResidentApiController;
use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->group(function(){

Route::get('/residents',
    [ResidentApiController::class, 'index']);

Route::get('/residents/{id}',
    [ResidentApiController::class, 'show']);

});

//login
Route::post('/login',[AuthController::class, 'login']);