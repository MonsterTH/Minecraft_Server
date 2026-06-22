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

#[Description('Remove a player from the Minecraft server whitelist.')]
#[IsDestructive]
class WhitelistRemove extends Tool
{
    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (!$user) {
            return Response::error('Unauthorized.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:16',
            'confirm'  => 'required|boolean',
        ]);

        if (!$data['confirm']) {
            return Response::error('You must confirm before removing this player from the whitelist.');
        }

        $rcon = app(RconService::class);
        $rconResponse = $rcon->send('whitelist remove ' . $data['username']);

        AuditLog::create([
            'admin_id' => $user->id,
            'action'   => 'whitelist_remove',
            'payload'  => ['username' => $data['username']],
            'response' => $rconResponse,
            'source'   => 'ai',
        ]);

        return Response::json([
            'success'  => true,
            'message'  => "{$data['username']} removed from whitelist.",
            'response' => $rconResponse,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'username' => $schema->string()
                ->description('The Minecraft username to remove from the whitelist')
                ->required(),

            'confirm' => $schema->boolean()
                ->description('Must be TRUE only if the user explicitly confirmed removal (never guess)')
                ->required(),
        ];
    }
}
