<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.auth');

Route::post('/auth/register', [AuthController::class, 'store']);

Route::post('/auth/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
Route::post('/profiles/verify-pin', [ProfileController::class, 'verifyPin'])->name('profiles.verifyPin');

Route::middleware(['auth'])->group(function () {
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('saving-goals', SavingGoalController::class);
    Route::resource('budgets', BudgetController::class);
});
Route::get('/saving-goals', [SavingGoalController::class, 'index'])->name('saving-goals');

Route::get('/dashboard', [TransactionController::class, 'index']);
