<?php

namespace App\Services;

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class MinecraftQueryService
{
    public function getStatus(): array
    {
        $ping = null;

        try {
            $ping = new MinecraftPing('172.17.0.1', 25565, 2);
            $info = $ping->Query();

            return [
                'online'         => true,
                'motd'           => $info['description']['text'] ?? $info['description'] ?? 'Unknown',
                'version'        => $info['version']['name']     ?? 'Unknown',
                'players_online' => $info['players']['online']   ?? 0,
                'max_players'    => $info['players']['max']      ?? 0,
                'player_list'    => $info['players']['sample']   ?? [],
            ];
        } catch (MinecraftPingException $e) {
            return [
                'online' => false,
                'error'  => $e->getMessage(),
            ];
        } finally {
            $ping?->Close();
        }
    }
}
