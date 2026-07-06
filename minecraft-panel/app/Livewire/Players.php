<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServerEvent;
use App\Services\MinecraftQueryService;

class Players extends Component
{
    private function onlinePlayers()
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

    public function render(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        $onlineNames = $this->onlinePlayers()
            ->pluck('player_name')
            ->toArray();

        $players = ServerEvent::selectRaw("
            player_name,
            SUM(event_type = 'join') as joins,
            SUM(event_type = 'chat') as messages,
            SUM(event_type = 'death') as deaths,
            SUM(event_type = 'advancement') as advancements
        ")
        ->groupBy('player_name')
        ->get()
        ->map(function ($player) use ($onlineNames) {
            $player->is_online = in_array($player->player_name, $onlineNames);
            return $player;
        });

        $joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $totalEvents = ServerEvent::count();

        return view('livewire.players', [
            'players' => $players,
            'joinsToday' => $joinsToday,
            'totalEvents' => $totalEvents,
            'status' => $status,
        ]);
    }
}
