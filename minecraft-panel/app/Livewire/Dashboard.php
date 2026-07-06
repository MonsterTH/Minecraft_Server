<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServerEvent;
use App\Services\MinecraftQueryService;

class Dashboard extends Component
{
    public $status;
    public $topPlayers;
    public $joinsToday;
    public $chatFeed;

    public function mount(MinecraftQueryService $query)
    {
        $this->loadData($query);
    }

    public function loadData(MinecraftQueryService $query)
    {
        $this->status = $query->getStatus();

        $this->joinsToday = ServerEvent::where('event_type', 'join')
            ->whereDate('event_time', today())
            ->count();

        $this->chatFeed = ServerEvent::where('event_type', 'chat')
            ->latest('event_time')
            ->limit(20)
            ->get()
            ->map(function ($msg) {
                $msg->time = $msg->event_time->format('H:i');
                return $msg;
            });

        $this->topPlayers = ServerEvent::selectRaw('player_name, COUNT(*) as total')
            ->where('event_type', 'chat')
            ->groupBy('player_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function refresh(MinecraftQueryService $query)
    {
        $this->loadData($query);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
