<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use App\Models\ServerEvent;

#[Description('Get the list of players currently online, derived from join/leave events in the server logs.')]
#[IsReadOnly]
class GetOnlinePlayers extends Tool
{
    public function handle(Request $request): Response
    {
        $onlinePlayers = ServerEvent::where('event_type', 'join')
            ->whereNotIn('player_name', function ($q) {
                $q->select('player_name')
                    ->from('server_events')
                    ->where('event_type', 'leave');
            })
            ->get(['player_name', 'event_time']);

        return Response::json([
            'count'   => $onlinePlayers->count(),
            'players' => $onlinePlayers->map(fn ($p) => [
                'name'       => $p->player_name,
                'joined_at'  => $p->event_time,
            ]),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
