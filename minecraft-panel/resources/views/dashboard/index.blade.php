@extends('layouts.app')
@section('content')

    <h1>Dashboard</h1>

    <h2>Players Online ({{ $onlinePlayers->count() }})</h2>

    @foreach($onlinePlayers as $player)
        <div>{{ $player->player_name }}</div>
    @endforeach

    <hr>

    <h2>Joins Today: {{ $joinsToday }}</h2>

    <hr>

    <h2>Chat</h2>

    @foreach($chatFeed as $msg)
        <div>
            <strong>{{ $msg->player_name }}</strong>:
            {{ $msg->message }}
            <small>{{ $msg->event_time }}</small>
        </div>
    @endforeach

    <hr>

    <h2>Top Players</h2>

    @foreach($topPlayers as $player)
        <div>
            {{ $player->player_name }} - {{ $player->total }}
        </div>
    @endforeach

@endsection
