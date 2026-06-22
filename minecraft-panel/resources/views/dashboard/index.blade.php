@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <!-- STAT CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Players Online</p>
            <p class="text-2xl font-bold text-white">{{ $status['players_online'] ?? 0}}</p>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Joins Today</p>
            <p class="text-2xl font-bold text-white">{{ $joinsToday }}</p>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Chat Messages Today</p>
            <p class="text-2xl font-bold text-white">{{ $chatFeed->count() }}</p>
        </div>

        @if($status['online'])
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs text-muted mb-1">Server Status</p>
                <p class="text-2xl font-bold text-green-400">🟢 Online</p>
            </div>
        @else
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs text-muted mb-1">Server Status</p>
                <p class="text-2xl font-bold text-green-400">🔴 Offline</p>
            </div>
        @endif
    </div>

    <!-- PLAYERS + TOP PLAYERS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        <!-- Players Online -->
        <div class="bg-card border border-border rounded-xl p-4">
            <h2 class="text-sm font-semibold text-white mb-3">
                👥 Players Online
            </h2>

            @forelse($status['player_list'] as $player)

                <div class="flex items-center gap-3 py-2 border-b border-border">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    <span class="text-sm text-gray-300">
                        {{ $player['name'] }}
                    </span>
                </div>

            @empty

                <p class="text-sm text-muted">
                    No players online.
                </p>

            @endforelse
        </div>

        <!-- Top Players -->
        <div class="bg-card border border-border rounded-xl p-4">
            <h2 class="text-sm font-semibold text-white mb-3">
                🏆 Top Players by Activity
            </h2>

            @forelse($topPlayers as $i => $player)
                <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-muted w-4">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-300">{{ $player->player_name }}</span>
                    </div>
                    <span class="text-xs text-muted">{{ $player->total }} events</span>
                </div>
            @empty
                <p class="text-sm text-muted">No data yet.</p>
            @endforelse
        </div>

    </div>

    <!-- CHAT FEED (live via Livewire) -->
    <div class="bg-card border border-border rounded-xl p-4">
        <h2 class="text-sm font-semibold text-white mb-3">
            💬 Recent Chat
            <span class="ml-2 text-xs text-muted font-normal">(updates every 5s)</span>
        </h2>

        <livewire:dashboard />

    </div>

@endsection
