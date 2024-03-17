<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/' , [\App\Http\Controllers\Api\HomeController::class , 'index']);

Route::post('/register' , [\App\Http\Controllers\Api\Auth\AuthController::class , 'register']);

Route::post('/login' , [\App\Http\Controllers\Api\Auth\AuthController::class , 'login']);
Route::post('/logout' , [\App\Http\Controllers\Api\Auth\AuthController::class , 'logout']);

Route::get('/users' , [\App\Http\Controllers\Api\HomeController::class , 'users']);
