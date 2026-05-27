<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PesanKontak extends Model
{
    protected $table = 'pesan_kontak';

    protected $fillable = [
        'nama',
        'kontak',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read messages.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }
}
