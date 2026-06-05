<?php

namespace App\Services;

use App\Models\ServerEvent;

class DashboardService
{
    public function onlinePlayers()
    {
        return ServerEvent::query()
            ->select('player_name')
            ->whereIn('event_type', ['join', 'leave'])
            ->orderByDesc('event_time')
            ->get()
            ->groupBy('player_name')
            ->map(fn ($events) => $events->first())
            ->filter(fn ($event) => $event->event_type === 'join')
            ->values();
    }
}
