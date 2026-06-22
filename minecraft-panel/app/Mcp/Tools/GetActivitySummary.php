<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use App\Models\ServerEvent;
use Carbon\Carbon;

#[Description('Get an activity summary (joins, chat messages, deaths, advancements) for a given period: today, week, or month.')]
#[IsReadOnly]
class GetActivitySummary extends Tool
{
    public function handle(Request $request): Response
    {
        $period = $request->get('period', 'today');

        $from = match ($period) {
            'today' => Carbon::today(),
            'week'  => Carbon::now()->subDays(7),
            'month' => Carbon::now()->subDays(30),
            default => Carbon::today(),
        };

        $query = ServerEvent::where('event_time', '>=', $from);

        $summary = [
            'period'        => $period,
            'from'          => $from->toDateTimeString(),
            'joins'         => (clone $query)->where('event_type', 'join')->count(),
            'leaves'        => (clone $query)->where('event_type', 'leave')->count(),
            'chat_messages' => (clone $query)->where('event_type', 'chat')->count(),
            'deaths'        => (clone $query)->where('event_type', 'death')->count(),
            'advancements'  => (clone $query)->where('event_type', 'advancement')->count(),
            'unique_players' => (clone $query)->distinct('player_name')->count('player_name'),
        ];

        return Response::json($summary);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'period' => $schema->string()
                ->description('Period to summarize: today, week or month')
                ->enum(['today', 'week', 'month'])
                ->required(),
        ];
    }
}
