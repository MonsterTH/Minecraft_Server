<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServerEvent;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'onlinePlayers' => ServerEvent::where('event_type', 'join')
                ->whereDate('event_time', today())
                ->count(),

            'chatFeed' => ServerEvent::where('event_type', 'chat')
                ->latest('event_time')
                ->limit(20)
                ->get(),
        ]);
    }
}
