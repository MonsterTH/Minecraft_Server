{{-- resources/views/livewire/dashboard.blade.php --}}
<div wire:poll.1s>

    @forelse($chatFeed as $msg)
        <div class="flex items-start gap-3 py-2 border-b border-border last:border-0">

            <div class="flex-1">
                <span class="text-sm font-semibold text-accent-hover">
                    {{ $msg->player_name }}
                </span>
                <span class="text-sm text-gray-300 ml-2">
                    {{ $msg->message }}
                </span>
            </div>

            <span class="text-xs text-muted shrink-0">
                {{ \Carbon\Carbon::parse($msg->event_time)->format('H:i') }}
            </span>

        </div>
    @empty
        <p class="text-sm text-muted">No chat messages yet.</p>
    @endforelse

</div>
