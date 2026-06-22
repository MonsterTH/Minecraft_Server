<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

// Read tools
use App\Mcp\Tools\GetServerStatus;
use App\Mcp\Tools\GetOnlinePlayers;
use App\Mcp\Tools\GetActivitySummary;
use App\Mcp\Tools\GetPlayer;

// Action tools
use App\Mcp\Tools\BroadcastMessage;
use App\Mcp\Tools\WhitelistAdd;
use App\Mcp\Tools\WhitelistRemove;
use App\Mcp\Tools\KickPlayer;

#[Name('MinecraftServer')]
#[Version('1.0.0')]
#[Instructions(
    'This server allows monitoring and administering a Minecraft server.
    Use GetServerStatus, GetOnlinePlayers, GetActivitySummary and GetPlayer to check information.
    Use BroadcastMessage to send announcements.
    Use WhitelistAdd/WhitelistRemove to manage the whitelist.
    Use KickPlayer to remove a player from the server.
    ALWAYS confirm with the user before kicking a player or removing them from the whitelist — never guess the confirm field as true.
    Every administrative action is logged in the audit log with source=ai.'
)]
class MinecraftServer extends Server
{
    protected array $tools = [
        GetServerStatus::class,
        GetOnlinePlayers::class,
        GetActivitySummary::class,
        GetPlayer::class,
        BroadcastMessage::class,
        WhitelistAdd::class,
        WhitelistRemove::class,
        KickPlayer::class,
    ];

    protected array $resources = [];

    protected array $prompts = [];
}
