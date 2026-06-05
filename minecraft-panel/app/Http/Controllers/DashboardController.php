<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\ServerEvent;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $onlinePlayers = $dashboardService->onlinePlayers();

        $joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $chatFeed = ServerEvent::where('event_type', 'chat')
            ->latest('event_time')
            ->limit(50)
            ->get();

        $topPlayers = ServerEvent::query()
            ->selectRaw('player_name, COUNT(*) as total')
            ->groupBy('player_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'onlinePlayers',
            'joinsToday',
            'chatFeed',
            'topPlayers'
        ));
    }
}
