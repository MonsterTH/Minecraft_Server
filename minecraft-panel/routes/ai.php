<?php

use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\MinecraftServer;

Mcp::web('/mcp/minecraft', MinecraftServer::class);
