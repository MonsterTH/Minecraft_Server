@extends('layouts.app')
@section('title', 'Admin Actions')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- BROADCAST MESSAGE -->
        <div class="bg-card border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">📢 Broadcast Message</h2>

            <form method="POST" action="/panel/command">
                @csrf
                <input type="hidden" name="command_type" value="broadcast">

                <div class="mb-3">
                    <label class="block text-xs text-muted mb-1">Message</label>
                    <input
                        type="text"
                        name="message"
                        placeholder="Server will restart in 10 minutes..."
                        class="w-full bg-dark border border-border rounded-lg px-3 py-2 text-sm text-white
                               placeholder:text-muted focus:outline-none focus:border-accent"
                        required
                    >
                </div>

                <button type="submit"
                    class="w-full bg-accent hover:bg-accent-hover text-white text-sm font-semibold
                           py-2 rounded-lg transition">
                    Send Broadcast
                </button>
            </form>
        </div>

        <!-- KICK PLAYER -->
        <div class="bg-card border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">🥾 Kick Player</h2>

            <form method="POST" action="/panel/command"
                  onsubmit="return confirm('Are you sure you want to kick this player?')">
                @csrf
                <input type="hidden" name="command_type" value="kick">

                <div class="mb-3">
                    <label class="block text-xs text-muted mb-1">Player Name</label>
                    <input
                        type="text"
                        name="player"
                        placeholder="Steve"
                        class="w-full bg-dark border border-border rounded-lg px-3 py-2 text-sm text-white
                               placeholder:text-muted focus:outline-none focus:border-accent"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="block text-xs text-muted mb-1">Reason (optional)</label>
                    <input
                        type="text"
                        name="reason"
                        placeholder="Breaking rules"
                        class="w-full bg-dark border border-border rounded-lg px-3 py-2 text-sm text-white
                               placeholder:text-muted focus:outline-none focus:border-accent"
                    >
                </div>

                <button type="submit"
                    class="w-full bg-yellow-600 hover:bg-yellow-500 text-white text-sm font-semibold
                           py-2 rounded-lg transition">
                    Kick Player
                </button>
            </form>
        </div>

        <!-- WHITELIST ADD -->
        <div class="bg-card border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">✅ Whitelist Add</h2>

            <form method="POST" action="/panel/command">
                @csrf
                <input type="hidden" name="command_type" value="whitelist_add">

                <div class="mb-3">
                    <label class="block text-xs text-muted mb-1">Player Name</label>
                    <input
                        type="text"
                        name="player"
                        placeholder="Steve"
                        class="w-full bg-dark border border-border rounded-lg px-3 py-2 text-sm text-white
                               placeholder:text-muted focus:outline-none focus:border-accent"
                        required
                    >
                </div>

                <button type="submit"
                    class="w-full bg-accent hover:bg-accent-hover text-white text-sm font-semibold
                           py-2 rounded-lg transition">
                    Add to Whitelist
                </button>
            </form>
        </div>

        <!-- WHITELIST REMOVE -->
        <div class="bg-card border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">❌ Whitelist Remove</h2>

            <form method="POST" action="/panel/command"
                  onsubmit="return confirm('Remove this player from the whitelist?')">
                @csrf
                <input type="hidden" name="command_type" value="whitelist_remove">

                <div class="mb-3">
                    <label class="block text-xs text-muted mb-1">Player Name</label>
                    <input
                        type="text"
                        name="player"
                        placeholder="Steve"
                        class="w-full bg-dark border border-border rounded-lg px-3 py-2 text-sm text-white
                               placeholder:text-muted focus:outline-none focus:border-accent"
                        required
                    >
                </div>

                <button type="submit"
                    class="w-full bg-danger hover:bg-red-500 text-white text-sm font-semibold
                           py-2 rounded-lg transition">
                    Remove from Whitelist
                </button>
            </form>
        </div>

    </div>

    <!-- COMMAND RESPONSE -->
    @if(session('response'))
        <div class="mt-4 bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-muted mb-2">RCON Response</p>
            <pre class="text-sm text-green-400 font-mono">{{ session('response') }}</pre>
        </div>
    @endif

@endsection
