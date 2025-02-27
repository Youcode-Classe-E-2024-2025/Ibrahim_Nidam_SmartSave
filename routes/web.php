<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.auth');
Route::view('/home', 'home');

Route::post('/auth/register', [AuthController::class, 'store']);

Route::post('/auth/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
Route::post('/profiles/verify-pin', [ProfileController::class, 'verifyPin'])->name('profiles.verifyPin');