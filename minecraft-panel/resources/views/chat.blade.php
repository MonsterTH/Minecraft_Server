@extends('layouts.app')

@section('title', 'Chat')

@section('content')

<!-- TOP STATS -->

<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="bg-card border border-border rounded-xl p-4">

        <p class="text-xs text-muted mb-1">

            Messages Today

        </p>

        <p class="text-2xl font-bold text-white">

            {{ $messagesToday }}

        </p>

    </div>

    <div class="bg-card border border-border rounded-xl p-4">

        <p class="text-xs text-muted mb-1">

            Active Players

        </p>

        <p class="text-2xl font-bold text-white">

            {{ $activePlayers }}

        </p>

    </div>

    <div class="bg-card border border-border rounded-xl p-4">

        <p class="text-xs text-muted mb-1">

            Feed Updates

        </p>

        <p class="text-2xl font-bold text-green-400">

            Every 5s

        </p>

    </div>

</div>

<!-- SEARCH -->

<div class="bg-card border border-border rounded-xl p-4 mb-6">

    <form method="GET">

        <div class="flex gap-3">

            <input

                type="text"

                name="search"

                value="{{ request('search') }}"

                placeholder="Search player or message..."

                class="flex-1 bg-dark border border-border rounded-lg px-3 py-2
                       text-sm text-white placeholder:text-muted
                       focus:outline-none focus:border-accent"

            >

            <button

                class="bg-accent hover:bg-accent-hover
                       px-5 rounded-lg text-sm font-semibold">

                Search

            </button>

        </div>

    </form>

</div>

<!-- CHAT FEED -->

<div class="bg-card border border-border rounded-xl p-4">

    <div class="flex items-center justify-between mb-4">

        <h2 class="text-sm font-semibold text-white">

            💬 Chat Feed

        </h2>

        <span class="text-xs text-muted">

            Last 50 messages

        </span>

    </div>

    <div class="space-y-3 max-h-[700px] overflow-y-auto">

        @forelse($chatMessages as $message)

            <div class="border-b border-border pb-3">

                <div class="flex items-center justify-between mb-1">

                    <div class="flex items-center gap-2">

                        <span class="text-accent font-semibold">

                            {{ $message->player_name }}

                        </span>

                    </div>

                    <span class="text-xs text-muted">

                        {{ $message->event_time->diffForHumans() }}

                    </span>

                </div>

                <p class="text-sm text-gray-300">

                    {{ $message->message }}

                </p>

            </div>

        @empty

            <p class="text-sm text-muted">

                No chat messages found.

            </p>

        @endforelse

    </div>

</div>

<!-- LIVEWIRE -->

<div class="mt-6">

    <livewire:dashboard />

</div>

@endsection
