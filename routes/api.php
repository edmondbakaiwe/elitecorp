<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/inscription', [UserController::class, 'inscription']);
Route::get('/connexion', [UserController::class, 'connexion']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
