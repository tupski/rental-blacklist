<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavbarMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'icon',
        'order',
        'is_active',
        'open_new_tab',
        'visibility',
        'route_name',
        'route_params',
        'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_new_tab' => 'boolean',
        'route_params' => 'array',
        'order' => 'integer',
    ];

    /**
     * Get the parent menu
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavbarMenu::class, 'parent_id');
    }

    /**
     * Get the child menus
     */
    public function children(): HasMany
    {
        return $this->hasMany(NavbarMenu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for main menus (no parent)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for ordered menus
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope for visibility
     */
    public function scopeForUser($query, $user = null)
    {
        if (!$user) {
            return $query->where('visibility', 'all')->orWhere('visibility', 'guest');
        }

        $visibilities = ['all', 'auth'];

        if ($user->role === 'admin') {
            $visibilities[] = 'admin';
        } elseif ($user->role === 'pengusaha_rental') {
            $visibilities[] = 'rental';
        }

        return $query->whereIn('visibility', $visibilities);
    }

    /**
     * Get the full URL for this menu
     */
    public function getFullUrlAttribute()
    {
        if ($this->route_name) {
            try {
                return route($this->route_name, $this->route_params ?? []);
            } catch (\Exception $e) {
                return $this->url;
            }
        }

        return $this->url;
    }

    /**
     * Check if this menu is currently active
     */
    public function isCurrentlyActive()
    {
        if ($this->route_name) {
            return request()->routeIs($this->route_name);
        }

        return request()->url() === $this->full_url;
    }
}
