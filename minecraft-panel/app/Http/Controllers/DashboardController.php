<?php

namespace App\Http\Controllers;

use App\Models\ServerEvent;
use App\Services\MinecraftQueryService;

class DashboardController extends Controller
{
    // ✅ Método reutilizável para não repetir a query
    private function onlinePlayersQuery()
    {
        return ServerEvent::select('player_name')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('server_events')
                    ->whereIn('event_type', ['join', 'leave'])
                    ->groupBy('player_name');
            })
            ->where('event_type', 'join');
    }

    public function index(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        $onlinePlayers = $this->onlinePlayersQuery()->get();

        $joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $chatFeed = ServerEvent::where('event_type', 'chat')
            ->latest('event_time')
            ->limit(50)
            ->get();

        $topPlayers = ServerEvent::selectRaw('player_name, COUNT(*) as total')
            ->where('event_type', 'chat')
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

    public function getOnlinePlayers()
    {
        return $this->onlinePlayersQuery()->get(); // ✅ fix
    }

    public function players(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        $onlineNames = $this->onlinePlayersQuery() // ✅ fix
            ->pluck('player_name')
            ->toArray();

        $players = ServerEvent::selectRaw('
                player_name,
                SUM(event_type = "join") as joins,
                SUM(event_type = "chat") as messages,
                SUM(event_type = "death") as deaths,
                SUM(event_type = "advancement") as advancements
            ')
            ->groupBy('player_name')
            ->get()
            ->map(function ($p) use ($onlineNames) {
                $p->is_online = in_array($p->player_name, $onlineNames);
                return $p;
            });

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
            'players'      => $players,
            'onlineNames'  => $onlineNames,
            'topPlayers'   => $topPlayers,
            'joinsToday'   => $joinsToday,
            'totalEvents'  => $totalEvents,
            'status'       => $status,
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
            'chatMessages'  => $chatMessages,
            'messagesToday' => $messagesToday,
            'activePlayers' => $activePlayers,
        ]);
    }
}
