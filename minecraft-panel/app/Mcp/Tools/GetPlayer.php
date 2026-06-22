<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use App\Models\ServerEvent;

#[Description('Get statistics for a specific player: joins, messages, deaths, advancements, and online status.')]
#[IsReadOnly]
class GetPlayer extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'username' => 'required|string',
        ]);

        $username = $data['username'];

        $exists = ServerEvent::where('player_name', $username)->exists();

        if (!$exists) {
            return Response::error("No data found for player '{$username}'.");
        }

        $isOnline = ServerEvent::where('player_name', $username)
            ->where('event_type', 'join')
            ->whereDoesntExist(function ($q) use ($username) {
                $q->select('id')
                    ->from('server_events as leave_events')
                    ->whereColumn('leave_events.player_name', 'server_events.player_name')
                    ->where('leave_events.event_type', 'leave')
                    ->whereColumn('leave_events.event_time', '>', 'server_events.event_time');
            })
            ->exists();

        $stats = ServerEvent::where('player_name', $username)
            ->selectRaw('
                SUM(event_type = "join") as joins,
                SUM(event_type = "leave") as leaves,
                SUM(event_type = "chat") as messages,
                SUM(event_type = "death") as deaths,
                SUM(event_type = "advancement") as advancements,
                MAX(event_time) as last_seen
            ')
            ->first();

        $recentDeaths = ServerEvent::where('player_name', $username)
            ->where('event_type', 'death')
            ->latest('event_time')
            ->limit(3)
            ->pluck('message');

        return Response::json([
            'username'      => $username,
            'online'        => $isOnline,
            'joins'         => (int) $stats->joins,
            'leaves'        => (int) $stats->leaves,
            'messages'      => (int) $stats->messages,
            'deaths'        => (int) $stats->deaths,
            'advancements'  => (int) $stats->advancements,
            'last_seen'     => $stats->last_seen,
            'recent_deaths' => $recentDeaths,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'username' => $schema->string()
                ->description('The Minecraft username to look up')
                ->required(),
        ];
    }
}
