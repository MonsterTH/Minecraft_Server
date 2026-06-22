@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen">

    <div class="bg-card border border-border p-6 rounded-xl w-96">

        <h1 class="text-white text-xl mb-4">Login</h1>

        <form method="POST" action="/login">
            @csrf

            <input
                type="email"
                name="email"
                placeholder="Email"
                class="w-full mb-3 bg-dark border border-border rounded px-3 py-2 text-white"
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                class="w-full mb-3 bg-dark border border-border rounded px-3 py-2 text-white"
            >

            <button class="w-full bg-accent text-white py-2 rounded">
                Login
            </button>
        </form>

        @error('email')
            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
        @enderror

    </div>

</div>

@endsection
