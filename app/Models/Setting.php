<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    // Helper method untuk get setting value
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method untuk set setting value
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    // Get settings by group
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key');
    }
}
