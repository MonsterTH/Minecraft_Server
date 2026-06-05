<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MinecraftController;
use App\Http\Controllers\MinecraftPanelController;
use App\Http\Controllers\DashboardController;

Route::get('/minecraft', [MinecraftController::class, 'status']);

Route::get('/panel', [MinecraftPanelController::class, 'index']);
Route::post('/panel/command', [MinecraftPanelController::class, 'command']);


Route::get('/', [DashboardController::class, 'index']);
