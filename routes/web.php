<?php

use App\Http\Controllers\auth\Login;
use App\Http\Controllers\auth\Logout;
use App\Http\Controllers\auth\Register;
use App\Http\Controllers\ChirpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChirpController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::post('/chirps', [ChirpController::class, 'store']);
    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit']);
    Route::put('/chirps/{chirp}', [ChirpController::class, 'update']);
    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy']);
});

//jalan lain
// Route::resource('chirps', ChirpController::class)
//         ->only(['index', 'store', 'edit', 'update', 'destroy']);

//Registration routes
Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');
Route::post('/register', Register::class)
    ->middleware('guest');

//LOGOUT routes
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');

//LOGIN routes
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');
Route::post('/login', Login::class)
    ->middleware('guest');
