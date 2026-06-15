@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')

    <div class="bg-card border border-border rounded-xl p-4">
        <h2 class="text-sm font-semibold text-white mb-4">📋 Admin Actions History</h2>

        @forelse($logs as $log)
            <div class="flex items-start justify-between py-3 border-b border-border last:border-0">

                <div class="flex items-start gap-3">
                    <span class="text-lg">
                        @switch($log->action)
                            @case('broadcast') 📢 @break
                            @case('kick') 🥾 @break
                            @case('whitelist_add') ✅ @break
                            @case('whitelist_remove') ❌ @break
                            @default ⚙️
                        @endswitch
                    </span>

                    <div>
                        <p class="text-sm text-white font-medium">
                            {{ str_replace('_', ' ', ucfirst($log->action)) }}
                        </p>
                        <p class="text-xs text-muted mt-0.5">
                            by {{ $log->admin->name ?? 'Unknown' }}
                        </p>
                        @if($log->response)
                            <p class="text-xs text-green-400 font-mono mt-1">
                                → {{ $log->response }}
                            </p>
                        @endif
                    </div>
                </div>

                <span class="text-xs text-muted shrink-0">
                    {{ $log->created_at->diffForHumans() }}
                </span>

            </div>
        @empty
            <p class="text-sm text-muted">No actions recorded yet.</p>
        @endforelse

        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

@endsection
