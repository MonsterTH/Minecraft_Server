<!DOCTYPE html>
<html>
<head>
    <title>Minecraft Panel</title>
</head>
<body>

    <h1>🔥 Minecraft Panel</h1>

    <!-- STATUS BOX -->
    <div style="padding:10px; border:1px solid #ccc; margin-bottom:20px;">
        <h2>Status</h2>

        @if($status['online'])
            <p>🟢 Online</p>
            <p>MOTD: {{ $status['motd'] }}</p>
            <p>Version: {{ $status['version'] }}</p>
            <p>Players: {{ $status['players_online'] }}/{{ $status['max_players'] }}</p>
        @else
            <p>🔴 Offline</p>
            <p>{{ $status['error'] ?? '' }}</p>
        @endif
    </div>

    <!-- RCON COMMAND BOX -->
    <div style="padding:10px; border:1px solid #ccc;">
        <h2>Send Command (RCON)</h2>

        <form method="POST" action="/panel/command">
            @csrf

            <input
                type="text"
                name="command"
                placeholder="say Hello"
                style="width:300px;"
            >

            <button type="submit">Send</button>
        </form>

        @if(session('response'))
            <h3>Response: </h3>
            <pre>{{ session('response') }}</pre>
        @endif
    </div>

</body>
</html>
