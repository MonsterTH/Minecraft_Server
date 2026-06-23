<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/minecraft/event', function (Request $request) {

    if (!$request->event || !$request->player) {
        return response()->json([
            'ok' => false,
            'error' => 'missing event or player'
        ], 400);
    }

    DB::table('server_events')->insert([
        'event_type' => $request->event,
        'player_name' => $request->player,
        'message' => $request->message,
        'metadata' => json_encode($request->all()),
        'event_time' => now(),
    ]);

    return response()->json(['ok' => true]);
});
