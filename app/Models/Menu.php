<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'location', // header, footer, sidebar, custom
        'items', // Array of items: [['title_ar' => '...', 'title_en' => '...', 'type' => 'link|category|product|page', 'value' => '...', 'children' => [...]], ...]
        'is_active',
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to only include active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
