<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use App\Services\RconService;
use App\Models\AuditLog;

#[Description('Broadcast a message to all players on the Minecraft server.')]
class BroadcastMessage extends Tool
{
    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (!$user) {
            return Response::error('Unauthorized.');
        }

        $data = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $rcon = app(RconService::class);
        $rconResponse = $rcon->send('say ' . $data['text']);

        AuditLog::create([
            'admin_id' => $user->id,
            'action'   => 'broadcast',
            'payload'  => ['message' => $data['text']],
            'response' => $rconResponse,
            'source'   => 'ai',
        ]);

        return Response::json([
            'success'  => true,
            'message'  => 'Broadcast sent.',
            'response' => $rconResponse,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'text' => $schema->string()
                ->description('The message to broadcast to all players')
                ->required(),
        ];
    }
}
