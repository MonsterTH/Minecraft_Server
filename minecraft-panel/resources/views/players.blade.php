@extends('layouts.app')

@section('title', 'Players')

@section('content')

<!-- TOP STATS -->

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-card border border-border rounded-xl p-4">
        <p class="text-xs text-muted mb-1">Players Online</p>

        <p class="text-2xl font-bold text-white">
            {{ $onlinePlayers->count() }}
        </p>
    </div>

    <div class="bg-card border border-border rounded-xl p-4">
        <p class="text-xs text-muted mb-1">Joins Today</p>

        <p class="text-2xl font-bold text-white">
            {{ $joinsToday }}
        </p>
    </div>

    <div class="bg-card border border-border rounded-xl p-4">
        <p class="text-xs text-muted mb-1">Total Players</p>

        <p class="text-2xl font-bold text-white">
            {{ $players->count() }}
        </p>
    </div>

    <div class="bg-card border border-border rounded-xl p-4">
        <p class="text-xs text-muted mb-1">Tracked Events</p>

        <p class="text-2xl font-bold text-white">
            {{ $totalEvents }}
        </p>
    </div>

</div>

<!-- PLAYERS TABLE -->

<div class="bg-card border border-border rounded-xl overflow-hidden">

    <div class="p-4 border-b border-border">

        <div class="flex items-center justify-between">

            <h2 class="text-sm font-semibold text-white">
                👥 Player Activity
            </h2>

            <span class="text-xs text-muted">
                Updated from latest.log
            </span>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-dark">

            <tr class="text-left text-xs text-muted uppercase">

                <th class="p-4">Player</th>

                <th class="p-4">Status</th>

                <th class="p-4">Joins</th>

                <th class="p-4">Messages</th>

                <th class="p-4">Deaths</th>

                <th class="p-4">Advancements</th>

                <th class="p-4">Playtime</th>

                <th class="p-4">Last Seen</th>

            </tr>

            </thead>

            <tbody>

            @forelse($players as $player)

                <tr class="border-t border-border hover:bg-white/5 transition">

                    <td class="p-4">

                        <div class="flex items-center gap-3">

                            <span class="w-2 h-2 rounded-full
                                {{ $player->is_online
                                    ? 'bg-green-400'
                                    : 'bg-gray-500' }}">
                            </span>

                            <span class="text-white font-medium">

                                {{ $player->player_name }}

                            </span>

                        </div>

                    </td>

                    <td class="p-4">

                        @if($player->is_online)

                            <span class="text-green-400 text-xs">

                                Online

                            </span>

                        @else

                            <span class="text-muted text-xs">

                                Offline

                            </span>

                        @endif

                    </td>

                    <td class="p-4 text-sm">

                        {{ $player->joins }}

                    </td>

                    <td class="p-4 text-sm">

                        {{ $player->messages }}

                    </td>

                    <td class="p-4 text-sm">

                        {{ $player->deaths }}

                    </td>

                    <td class="p-4 text-sm">

                        {{ $player->advancements }}

                    </td>

                    <td class="p-4 text-sm">

                        {{ $player->playtime }}

                    </td>

                    <td class="p-4 text-xs text-muted">

                        {{ optional($player->last_seen)->diffForHumans() }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="p-6 text-center text-muted">

                        No players found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
