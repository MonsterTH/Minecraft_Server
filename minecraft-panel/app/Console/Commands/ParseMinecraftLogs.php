<?php

namespace App\Console\Commands;

use App\Services\LogParser;
use Illuminate\Console\Command;

class ParseMinecraftLogs extends Command
{
    protected $signature = 'minecraft:parse-logs';

    protected $description = 'Parse latest minecraft logs';

    public function handle(LogParser $parser)
    {
        $parser->parse(
            storage_path('app/minecraft/logs/latest.log')
        );

        $this->info('Logs parsed.');
    }
}
