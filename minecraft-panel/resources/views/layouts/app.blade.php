<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Minecraft Panel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Inter, system-ui, sans-serif;
            background: #0f1115;
            color: #e5e7eb;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #161a22;
            padding: 20px;
            border-right: 1px solid #222836;
        }

        .sidebar h2 {
            font-size: 16px;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .sidebar a {
            display: block;
            padding: 10px 12px;
            margin-bottom: 6px;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #1f2430;
            color: #ffffff;
        }

        /* Main content */
        .main {
            margin-left: 240px;
            padding: 25px;
        }

        /* Cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }

        .card {
            background: #161a22;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #222836;
        }

        .card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #9ca3af;
        }

        .card .value {
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
        }

        /* Tables / lists */
        .box {
            background: #161a22;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #222836;
            margin-top: 15px;
        }

        .row {
            padding: 8px 0;
            border-bottom: 1px solid #222836;
        }

        .row:last-child {
            border-bottom: none;
        }

        /* Header */
        .topbar {
            margin-bottom: 20px;
        }

        .topbar h1 {
            font-size: 20px;
            margin: 0;
        }
    </style>
    @livewireStyles
</head>
<body>

    {{-- <div class="sidebar">
        <h2>MC Panel</h2>

        <a href="/dashboard">Dashboard</a>
        <a href="/players">Players</a>
        <a href="/chat">Chat</a>
        <a href="/admin">Admin</a>
    </div> --}}

    <div class="main">
        <div class="topbar">
            <h1>@yield('title', 'Dashboard')</h1>
        </div>

        @yield('content')
    </div>
    @livewireScripts
</body>
</html>
