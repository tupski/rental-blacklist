<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'value',
        'description',
        'order',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope for active attributes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for ordered attributes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Get attributes by type
     */
    public static function getByType($type, $activeOnly = true)
    {
        $query = static::ofType($type)->ordered();

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get all attribute types
     */
    public static function getTypes()
    {
        return [
            'jenis_rental' => 'Jenis Rental',
            'kategori_masalah' => 'Kategori Masalah',
            'status_penanganan' => 'Status Penanganan',
            'jenis_kendaraan' => 'Jenis Kendaraan',
            'merk_kendaraan' => 'Merk Kendaraan',
        ];
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        $types = static::getTypes();
        return $types[$this->type] ?? $this->type;
    }
}
