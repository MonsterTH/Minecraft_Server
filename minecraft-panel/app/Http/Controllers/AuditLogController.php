<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RconService;
use App\Models\AdminAction;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AdminAction::with('admin')->latest()->paginate(20);
        return view('audit', compact('logs'));
    }
}
