<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParserState extends Model
{
    protected $primaryKey = 'parser_name';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'parser_name',
        'last_position',
    ];
}
