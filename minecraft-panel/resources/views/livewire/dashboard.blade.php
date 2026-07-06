<div wire:poll.1s="refresh" class="space-y-6">

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Players Online</p>
            <p class="text-2xl font-bold text-white">
                {{ $status['players_online'] ?? 0 }}
            </p>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Joins Today</p>
            <p class="text-2xl font-bold text-white">
                {{ $joinsToday }}
            </p>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Chat Messages Today</p>
            <p class="text-2xl font-bold text-white">
                {{ $chatFeed->count() }}
            </p>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-1">Server Status</p>
            <p class="text-2xl font-bold {{ $status['online'] ? 'text-green-400' : 'text-red-400' }}">
                {{ $status['online'] ? '🟢 Online' : '🔴 Offline' }}
            </p>
        </div>

    </div>

    <!-- PLAYERS + TOP -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- TOP PLAYERS -->
        <div class="bg-card border border-border rounded-xl p-4">
            <h2 class="text-sm font-semibold text-white mb-3">
                🏆 Top Players
            </h2>

            @forelse($topPlayers as $i => $player)
                <div class="flex justify-between py-2 border-b border-border last:border-0">

                    <div class="flex gap-3">
                        <span class="text-xs text-muted w-4">{{ $i + 1 }}</span>
                        <span class="text-sm text-gray-300">{{ $player->player_name }}</span>
                    </div>

                    <span class="text-xs text-muted">
                        {{ $player->total }} events
                    </span>

                </div>
            @empty
                <p class="text-sm text-muted">No data yet.</p>
            @endforelse
        </div>

    </div>

    <!-- CHAT -->
    <div class="bg-card border border-border rounded-xl p-4 max-h-[400px] overflow-y-auto">

        <h2 class="text-sm font-semibold text-white mb-3">
            💬 Recent Chat <span class="text-xs text-muted">(live)</span>
        </h2>

        @forelse($chatFeed as $msg)
            <div class="flex justify-between py-2 border-b border-border last:border-0">

                <div>
                    <span class="text-sm font-semibold text-accent-hover">
                        {{ $msg->player_name }}
                    </span>

                    <span class="text-sm text-gray-300 ml-2">
                        {{ $msg->message }}
                    </span>
                </div>

                <span class="text-xs text-muted">
                    {{ $msg->time }}
                </span>

            </div>
        @empty
            <p class="text-sm text-muted">No chat messages yet.</p>
        @endforelse

    </div>

</div>
