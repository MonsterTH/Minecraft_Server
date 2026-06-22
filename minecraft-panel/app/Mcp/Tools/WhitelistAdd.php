<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use App\Services\RconService;
use App\Models\AuditLog;

#[Description('Add a player to the Minecraft server whitelist.')]
class WhitelistAdd extends Tool
{
    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (!$user) {
            return Response::error('Unauthorized.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:16',
        ]);

        $rcon = app(RconService::class);
        $rconResponse = $rcon->send('whitelist add ' . $data['username']);

        AuditLog::create([
            'admin_id' => $user->id,
            'action'   => 'whitelist_add',
            'payload'  => ['username' => $data['username']],
            'response' => $rconResponse,
            'source'   => 'ai',
        ]);

        return Response::json([
            'success'  => true,
            'message'  => "{$data['username']} added to whitelist.",
            'response' => $rconResponse,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'username' => $schema->string()
                ->description('The Minecraft username to add to the whitelist')
                ->required(),
        ];
    }
}
