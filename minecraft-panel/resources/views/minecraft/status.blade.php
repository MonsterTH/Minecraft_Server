@extends('layouts.app')
@section('content')
    <h1>Minecraft Server Status</h1>

    @if($status['online'])
        <p><strong>Status:</strong> 🟢 Online</p>
        <p><strong>MOTD:</strong> {{ $status['motd'] }}</p>
        <p><strong>Version:</strong> {{ $status['version'] }}</p>
        <p><strong>Players:</strong> {{ $status['players_online'] }}/{{ $status['max_players'] }}</p>

        @if(!empty($status['player_list']))
            <h3>Players Online:</h3>
            <ul>
                @foreach($status['player_list'] as $player)
                    <li>{{ $player['name'] ?? $player }}</li>
                @endforeach
            </ul>
        @else
            <p><em>Nenhum jogador online.</em></p>
        @endif
    @else
        <p>🔴 Server offline</p>
        <p>{{ $status['error'] ?? 'Unknown error' }}</p>
    @endif
@endsection
