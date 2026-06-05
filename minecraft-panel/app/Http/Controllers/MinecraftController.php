<?php

namespace App\Http\Controllers;

use App\Services\MinecraftQueryService;

class MinecraftController extends Controller
{
    public function status(MinecraftQueryService $query)
    {
        $status = $query->getStatus();

        return view('minecraft.status', compact('status'));
    }
}
