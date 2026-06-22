<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MinecraftController;
use App\Http\Controllers\MinecraftPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/minecraft', [MinecraftController::class, 'status']); // só serve para testar

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/players', [DashboardController::class, 'players']);
    Route::get('/chat', [DashboardController::class, 'chat']);

    Route::get('/admin', [MinecraftPanelController::class, 'admin']);

    Route::post('/panel/command', [MinecraftPanelController::class, 'sendCommand']);
    Route::post('/panel/refresh', [MinecraftPanelController::class, 'refreshLogs']);

    Route::get('/audit', [AuditLogController::class, 'index']);
});


