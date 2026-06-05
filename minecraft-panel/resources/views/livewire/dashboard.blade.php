<div wire:poll.5s>

    <h2>Dashboard Live</h2>

    <div>
        <strong>Online Players:</strong>
        {{ $onlinePlayers }}
    </div>

    <hr>

    <h3>Chat</h3>

    @foreach($chatFeed as $msg)
        <div>
            <strong>{{ $msg->player_name }}</strong>:
            {{ $msg->message }}
        </div>
    @endforeach

</div>
