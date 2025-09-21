<?php

namespace App\Models\Permission;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Enums\PermissionCategory;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'category',
        'description',
    ];

    protected $casts = [
        'category' => PermissionCategory::class,
    ];

    /**
     * Get permissions by category
     */
    public function scopeByCategory($query, PermissionCategory $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get all permissions grouped by category
     */
    public static function groupedByCategory()
    {
        return static::all()->groupBy('category');
    }
}