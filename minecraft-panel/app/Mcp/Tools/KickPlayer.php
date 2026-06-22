<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use App\Services\RconService;
use App\Models\AuditLog;

#[Description('Kick a player from the Minecraft server.')]
#[IsDestructive]
class KickPlayer extends Tool
{
    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (!$user) {
            return Response::error('Unauthorized.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:16',
            'reason'   => 'sometimes|string|max:255',
            'confirm'  => 'required|boolean',
        ]);

        if (!$data['confirm']) {
            return Response::error('You must confirm before kicking this player.');
        }

        $reason = $data['reason'] ?? 'Kicked by admin';

        $rcon = app(RconService::class);
        $rconResponse = $rcon->send("kick {$data['username']} {$reason}");

        AuditLog::create([
            'admin_id' => $user->id,
            'action'   => 'kick',
            'payload'  => [
                'username' => $data['username'],
                'reason'   => $reason,
            ],
            'response' => $rconResponse,
            'source'   => 'ai',
        ]);

        return Response::json([
            'success'  => true,
            'message'  => "{$data['username']} has been kicked.",
            'response' => $rconResponse,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'username' => $schema->string()
                ->description('The Minecraft username to kick')
                ->required(),

            'reason' => $schema->string()
                ->description('Reason for the kick')
                ->nullable(),

            'confirm' => $schema->boolean()
                ->description('Must be TRUE only if the user explicitly confirmed the kick (never guess)')
                ->required(),
        ];
    }
}
