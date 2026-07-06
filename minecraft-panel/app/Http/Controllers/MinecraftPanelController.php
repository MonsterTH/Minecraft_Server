<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RconService;
use App\Models\AdminAction;

class MinecraftPanelController extends Controller
{

    private array $allowedCommands = [
        'broadcast'       => 'say %s',
        'kick'            => 'kick %s %s',
        'whitelist_add'   => 'whitelist add %s',
        'whitelist_remove'=> 'whitelist remove %s',
    ];

    public function sendCommand(Request $request, RconService $rcon)
    {
        $type = $request->input('command_type');


        if ($type === 'raw') {

            $command = $request->input('command');

            if (!$command) {
                return back()->with('error', 'Empty command.');
            }

            $response = $rcon->send($command);

            AdminAction::create([
                'admin_id' => auth()->id(),
                'action'   => 'raw',
                'payload'  => ['command' => $command],
                'response' => $response,
            ]);

            return back()->with('response', $response);
        }


        if (!array_key_exists($type, $this->allowedCommands)) {
            return back()->with('error', 'Command not allowed.');
        }

        $command = match($type) {
            'broadcast' => sprintf(
                $this->allowedCommands[$type],
                $request->input('message')
            ),

            'kick' => sprintf(
                $this->allowedCommands[$type],
                $request->input('player'),
                $request->input('reason', 'Kicked by admin')
            ),

            'whitelist_add',
            'whitelist_remove' => sprintf(
                $this->allowedCommands[$type],
                $request->input('player')
            ),
        };

        $response = $rcon->send($command);

        AdminAction::create([
            'admin_id' => auth()->id(),
            'action'   => $type,
            'payload'  => $request->except(['_token']),
            'response' => $response,
        ]);

        return back()->with('response', $response);
    }

    public function refreshLogs()
    {
        app(\App\Services\LogParser::class)->parse(
            storage_path('logs/latest.log')
        );

        return back()->with('success', 'Logs refreshed.');
    }

    public function admin()
    {
        return view('admin');
    }
}
