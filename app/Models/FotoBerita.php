<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class FotoBerita extends Model
{
    protected $table = 'foto_berita';

    protected $fillable = [
        'berita_id',
        'foto',
        'keterangan',
        'urutan',
    ];

    /**
     * Get the news article that owns this photo.
     */
    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class, 'berita_id');
    }

    /**
     * Accessor to get the full public URL of the photo.
     */
    public function getFotoUrlAttribute(): string
    {
        if (filter_var($this->foto, FILTER_VALIDATE_URL)) {
            return $this->foto;
        }
        
        return Storage::url($this->foto);
    }

    /**
     * Scope a query to sort photos by their display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc');
    }
}
