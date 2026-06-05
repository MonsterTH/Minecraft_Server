<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MinecraftQueryService;
use Thedudeguy\Rcon;
use App\Models\AdminAction;

class MinecraftPanelController extends Controller
{
    public function index(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        return view('panel', compact('status'));
    }

    public function command(Request $request)
    {
        $request->validate([
            'command' => 'required|string'
        ]);

        $rcon = new \Thedudeguy\Rcon('172.17.0.1', 25575, 'secret', 3);

        $response = null;

        if ($rcon->connect()) {
            $response = $rcon->sendCommand($request->command);
        }

        AdminAction::create([
            'action' => 'rcon_command',
            'params' => $request->command,
            'source' => 'web',
            'result' => $response,
        ]);

        return back()->with('response', $response);
    }
}
