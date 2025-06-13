<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'key',
        'last_used_at',
        'is_active'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKey()
    {
        return 'rg_' . Str::random(60);
    }

    public function markAsUsed()
    {
        $this->update(['last_used_at' => now()]);
    }

    public static function findByKey($key)
    {
        return static::where('key', $key)
            ->where('is_active', true)
            ->first();
    }
}
