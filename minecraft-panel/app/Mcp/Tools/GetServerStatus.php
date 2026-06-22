<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use App\Services\RconService;
use App\Models\ServerEvent;

#[Description('Get the current Minecraft server status: online/offline, player count, version.')]
#[IsReadOnly]
class GetServerStatus extends Tool
{
    public function handle(Request $request): Response
    {
        try {
            $rcon = app(RconService::class);
            $response = $rcon->send('list');

            // Resposta típica: "There are 3 of a max of 20 players online: Steve, Alex, Bob"
            preg_match('/There are (\d+) of a max of (\d+) players online/', $response, $matches);

            $online = (int) ($matches[1] ?? 0);
            $max    = (int) ($matches[2] ?? 0);

            return Response::json([
                'status'         => 'online',
                'players_online' => $online,
                'max_players'    => $max,
                'raw_response'   => $response,
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'offline',
                'error'  => $e->getMessage(),
            ]);
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
