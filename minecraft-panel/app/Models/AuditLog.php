<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'payload',
        'response',
        'source', // ✅ 'panel' ou 'ai'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeFromAi($query)
    {
        return $query->where('source', 'ai');
    }

    public function scopeFromPanel($query)
    {
        return $query->where('source', 'panel');
    }
}
