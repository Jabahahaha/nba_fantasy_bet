<?php

use App\Http\Controllers\ContestController;
use App\Http\Controllers\LineupController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\RosterController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/rules', function () {
    return view('rules');
})->name('rules');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('userzone.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contests
    Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
    Route::get('/contests/{id}', [ContestController::class, 'show'])->name('contests.show');
    Route::get('/games', [ContestController::class, 'games'])->name('games.index');

    // Games
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');

    // Lineups
    Route::get('/lineups/create/{contest_id}', [LineupController::class, 'create'])->name('lineups.create');
    Route::post('/lineups', [LineupController::class, 'store'])->name('lineups.store');
    Route::get('/lineups', [LineupController::class, 'index'])->name('lineups.index');
    Route::get('/lineups/{id}', [LineupController::class, 'show'])->name('lineups.show');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/contests/create', [AdminController::class, 'createContest'])->name('contests.create');
    Route::post('/contests', [ContestController::class, 'store'])->name('contests.store');
    Route::post('/contests/{id}/simulate', [SimulationController::class, 'simulate'])->name('contests.simulate');

    // Data update routes
    Route::get('/update-data', [AdminController::class, 'updateData'])->name('update.data');
    Route::post('/update-roster', [AdminController::class, 'updateRoster'])->name('roster.update');
    Route::post('/update-schedule', [AdminController::class, 'updateSchedule'])->name('schedule.update');

    // Game simulation routes
    Route::get('/games', [App\Http\Controllers\Admin\GameSimulationController::class, 'index'])->name('games.index');
    Route::post('/games/simulate-date', [App\Http\Controllers\Admin\GameSimulationController::class, 'simulateDate'])->name('games.simulate-date');
    Route::post('/games/{game}/simulate', [App\Http\Controllers\Admin\GameSimulationController::class, 'simulateGame'])->name('games.simulate');
    Route::post('/games/reset-date', [App\Http\Controllers\Admin\GameSimulationController::class, 'resetDate'])->name('games.reset-date');
    Route::post('/games/{game}/reset', [App\Http\Controllers\Admin\GameSimulationController::class, 'resetGame'])->name('games.reset');

    // Roster manager
    Route::get('/rosters', [RosterController::class, 'index'])->name('rosters.index');
    Route::patch('/rosters/{player}', [RosterController::class, 'update'])->name('rosters.update');
    Route::post('/rosters/rebalance', [RosterController::class, 'rebalance'])->name('rosters.rebalance');
});

require __DIR__.'/auth.php';
