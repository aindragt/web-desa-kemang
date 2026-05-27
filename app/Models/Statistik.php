<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Statistik extends Model
{
    protected $table = 'statistik';

    protected $fillable = [
        'kategori',
        'label',
        'nilai',
        'satuan',
        'urutan',
    ];

    protected $casts = [
        'nilai' => 'float',
        'urutan' => 'integer',
    ];

    /**
     * Scope a query to filter statistics by category.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('kategori', $category);
    }

    /**
     * Scope a query to sort statistics by display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc');
    }
}
