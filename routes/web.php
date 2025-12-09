<?php

use App\Http\Controllers\UserTeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\PlayerController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [UserTeamController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/stats', [StatsController::class, 'index'])->name('stats');

Route::get('/players', [PlayerController::class, 'index'])->name('players');
Route::get('/players/{player}', [PlayerController::class, 'show'])->name('player.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
