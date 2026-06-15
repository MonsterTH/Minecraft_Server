<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MC Panel — @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#0d1117',
                        card: '#161b22',
                        border: '#30363d',
                        accent: '#238636',
                        'accent-hover': '#2ea043',
                        muted: '#8b949e',
                        danger: '#da3633',
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="bg-dark text-gray-200 font-sans">

    <!-- SIDEBAR -->
    <aside class="fixed top-0 left-0 h-full w-56 bg-card border-r border-border flex flex-col z-50">

        <!-- Logo -->
        <div class="px-5 py-5 border-b border-border">
            <div class="flex items-center gap-2">
                <span class="text-2xl">⛏️</span>
                <span class="font-bold text-white text-sm">MC Panel</span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1">

            <a href="/dashboard"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->is('dashboard') ? 'bg-accent text-white font-semibold' : 'text-muted hover:bg-white/5 hover:text-white' }}
               transition">
                <span>📊</span> Dashboard
            </a>

            <a href="/players"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->is('players') ? 'bg-accent text-white font-semibold' : 'text-muted hover:bg-white/5 hover:text-white' }}
               transition">
                <span>👥</span> Players
            </a>

            <a href="/chat"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->is('chat') ? 'bg-accent text-white font-semibold' : 'text-muted hover:bg-white/5 hover:text-white' }}
               transition">
                <span>💬</span> Chat
            </a>

            <a href="/admin"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->is('admin') ? 'bg-accent text-white font-semibold' : 'text-muted hover:bg-white/5 hover:text-white' }}
               transition">
                <span>⚙️</span> Admin
            </a>

            <a href="/audit"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->is('audit') ? 'bg-accent text-white font-semibold' : 'text-muted hover:bg-white/5 hover:text-white' }}
               transition">
                <span>📋</span> Audit Log
            </a>

        </nav>

        <!-- Server status badge -->
        <div class="px-5 py-4 border-t border-border">
            <div class="flex items-center gap-2 text-xs text-muted">
                <span class="w-2 h-2 rounded-full bg-accent-hover inline-block animate-pulse"></span>
                Server Online
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="ml-56 min-h-screen p-6">

        <!-- Top bar -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">@yield('title', 'Dashboard')</h1>

            <!-- Refresh button -->
            <form method="POST" action="/panel/refresh">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-card border border-border
                           rounded-lg text-sm text-muted hover:text-white hover:border-white/30 transition">
                    <span>🔄</span> Refresh Logs
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-accent/20 border border-accent/40 rounded-lg text-sm text-green-400">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-danger/20 border border-danger/40 rounded-lg text-sm text-red-400">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    @livewireScripts
</body>
</html>
