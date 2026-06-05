<?php

namespace App\Services;

use App\Models\ParserState;
use App\Models\ServerEvent;
use Carbon\Carbon;

class LogParser
{
    public function parse(string $filePath): void
    {
        if (! file_exists($filePath)) {
            return;
        }

        $state = ParserState::firstOrCreate(
            ['parser_name' => 'latest_log'],
            ['last_position' => 0]
        );

        $fp = fopen($filePath, 'r');

        fseek($fp, $state->last_position);

        while (($line = fgets($fp)) !== false) {
            $event = $this->parseLine(trim($line));

            if ($event) {
                ServerEvent::create($event);
            }
        }

        $state->update([
            'last_position' => ftell($fp)
        ]);

        fclose($fp);
    }

    private function parseLine(string $line): ?array
    {
        return
            $this->parseJoin($line)
            ?? $this->parseLeave($line)
            ?? $this->parseChat($line)
            ?? $this->parseAdvancement($line)
            ?? $this->parseDeath($line);
    }

    private function parseJoin(string $line): ?array
    {
        if (! preg_match(
            '/\[(\d{2}:\d{2}:\d{2})\].*?: ([A-Za-z0-9_]+) joined the game/',
            $line,
            $m
        )) {
            return null;
        }

        return [
            'event_type' => 'join',
            'player_name' => $m[2],
            'event_time' => $this->buildTime($m[1]),
        ];
    }

    private function parseLeave(string $line): ?array
    {
        if (! preg_match(
            '/\[(\d{2}:\d{2}:\d{2})\].*?: ([A-Za-z0-9_]+) left the game/',
            $line,
            $m
        )) {
            return null;
        }

        return [
            'event_type' => 'leave',
            'player_name' => $m[2],
            'event_time' => $this->buildTime($m[1]),
        ];
    }

    private function parseChat(string $line): ?array
    {
        if (! preg_match(
            '/\[(\d{2}:\d{2}:\d{2})\].*?: <([^>]+)> (.+)$/',
            $line,
            $m
        )) {
            return null;
        }

        return [
            'event_type' => 'chat',
            'player_name' => $m[2],
            'message' => $m[3],
            'event_time' => $this->buildTime($m[1]),
        ];
    }

    private function parseAdvancement(string $line): ?array
    {
        if (! preg_match(
            '/\[(\d{2}:\d{2}:\d{2})\].*?: ([A-Za-z0-9_]+) has made the advancement \[(.+?)\]/',
            $line,
            $m
        )) {
            return null;
        }

        return [
            'event_type' => 'advancement',
            'player_name' => $m[2],
            'metadata' => [
                'advancement' => $m[3],
            ],
            'event_time' => $this->buildTime($m[1]),
        ];
    }

    private function parseDeath(string $line): ?array
    {
        if (! preg_match(
            '/\[(\d{2}:\d{2}:\d{2})\].*?: ([A-Za-z0-9_]+) (.+)$/',
            $line,
            $m
        )) {
            return null;
        }

        $keywords = [
            'was slain',
            'was shot',
            'fell',
            'drowned',
            'blew up',
            'burned',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($m[3], $keyword)) {
                return [
                    'event_type' => 'death',
                    'player_name' => $m[2],
                    'message' => $m[3],
                    'event_time' => $this->buildTime($m[1]),
                ];
            }
        }

        return null;
    }

    private function buildTime(string $time): Carbon
    {
        return Carbon::parse(
            now()->format('Y-m-d') . ' ' . $time
        );
    }
}
