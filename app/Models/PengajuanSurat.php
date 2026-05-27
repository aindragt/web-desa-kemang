<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nomor_referensi',
        'jenis_surat',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'alamat',
        'no_hp',
        'keperluan',
        'nama_usaha',
        'jenis_usaha',
        'keterangan_tambahan',
        'status',
        'catatan_admin',
        'diproses_at',
        'disetujui_at',
        'disetujui_oleh',
        'selesai_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'diproses_at' => 'datetime',
        'disetujui_at' => 'datetime',
        'selesai_at' => 'datetime',
        'disetujui_oleh' => 'integer',
    ];

    /**
     * Get the user (Admin / Kepala Desa) who approved this letter.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Scope a query to only include pending letters.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope a query to only include letters currently in process.
     */
    public function scopeInProcess(Builder $query): Builder
    {
        return $query->where('status', 'diproses');
    }

    /**
     * Scope a query to only include letters waiting for approval from Kepala Desa.
     */
    public function scopeWaitingApproval(Builder $query): Builder
    {
        return $query->where('status', 'menunggu_persetujuan');
    }

    /**
     * Scope a query to only include approved letters.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Scope a query to only include rejected letters.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'ditolak');
    }

    /**
     * Scope a query to only include finished letters.
     */
    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope a query to search letters by their reference number.
     */
    public function scopeByReference(Builder $query, string $referenceNumber): Builder
    {
        return $query->where('nomor_referensi', $referenceNumber);
    }
}
