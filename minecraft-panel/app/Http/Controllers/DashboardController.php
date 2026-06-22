<?php

namespace App\Http\Controllers;

use App\Models\ServerEvent;
use App\Services\MinecraftQueryService;

class DashboardController extends Controller
{
    public function index(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        $onlinePlayers = ServerEvent::where('event_type', 'join')
            ->whereNotIn('player_name', function ($q) {
                $q->select('player_name')
                ->from('server_events')
                ->where('event_type', 'leave');
            })
            ->get();

        $joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $chatFeed = ServerEvent::where('event_type', 'chat')
            ->latest('event_time')
            ->limit(50)
            ->get();

        $topPlayers = ServerEvent::selectRaw('player_name, COUNT(*) as total')
            ->where('event_type', 'chat') // ou "all activity weighted"
            ->groupBy('player_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'onlinePlayers',
            'joinsToday',
            'chatFeed',
            'topPlayers',
            'status'
        ));


    }

    // players() e chat() para as rotas da sidebar
    public function players()
    {
        $players = ServerEvent::selectRaw('
                player_name,
                SUM(event_type = "join") as joins,
                SUM(event_type = "chat") as messages,
                SUM(event_type = "death") as deaths,
                SUM(event_type = "advancement") as advancements
            ')
            ->groupBy('player_name')
            ->get();

        $onlinePlayers = ServerEvent::where('event_type', 'join')
            ->whereNotIn('player_name', function ($q) {
                $q->select('player_name')
                ->from('server_events')
                ->where('event_type', 'leave');
            })
            ->get();

        $topPlayers = ServerEvent::selectRaw('player_name, COUNT(*) as total')
            ->groupBy('player_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $totalEvents = ServerEvent::count();

        return view('players', [
            'players' => $players,
            'onlinePlayers' => $onlinePlayers,
            'topPlayers' => $topPlayers,
            'joinsToday' => $joinsToday,
            'totalEvents' => $totalEvents,
        ]);
    }

    public function chat()
    {
        $chatMessages = ServerEvent::where('event_type', 'chat')
            ->latest('event_time')
            ->take(50)
            ->get();

        $messagesToday = ServerEvent::where('event_type', 'chat')
            ->whereDate('event_time', today())
            ->count();

        $activePlayers = ServerEvent::where('event_type', 'chat')
            ->whereDate('event_time', today())
            ->distinct()
            ->count('player_name');

        return view('chat', [
            'chatMessages' => $chatMessages,
            'messagesToday' => $messagesToday,
            'activePlayers' => $activePlayers,
        ]);
    }
}
