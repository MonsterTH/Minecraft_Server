<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerEvent extends Model
{
    protected $fillable = [
        'event_type',
        'player_name',
        'message',
        'metadata',
        'event_time',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_time' => 'datetime',
    ];
}
