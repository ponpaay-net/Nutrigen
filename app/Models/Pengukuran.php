<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengukuran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'balita_id',
        'kader_id',
        'tanggal_ukur',
        'umur_bulan',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'asi_eksklusif',
        'z_score_bbu',
        'z_score_tbu',
        'z_score_bbt',
        'status_gizi',
        'status_bbt',
        'status_tbu',
        'status_bbu',
        'status_kenaikan',
        'status_validasi',
        'catatan_validator',
        'validated_by',
        'validated_at',
        'catatan_kader',
        'rekomendasi_pmt',
        'is_susulan',
        'sesi_posyandu_id',
    ];

    protected $casts = [
        'tanggal_ukur' => 'date',
        'umur_bulan' => 'integer',
        'berat_badan' => 'float',
        'tinggi_badan' => 'float',
        'lingkar_kepala' => 'float',
        'asi_eksklusif' => 'boolean',
        'z_score_bbu' => 'float',
        'z_score_tbu' => 'float',
        'z_score_bbt' => 'float',
        'is_susulan' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function sesiPosyandu(): BelongsTo
    {
        return $this->belongsTo(SesiPosyandu::class);
    }

    /**
    * Scope a query to only include draft validations.
    */
    public function scopeDraft($query)
    {
        return $query->where('status_validasi', 'draft');
    }

    /**
    * Scope a query to only include pending validations.
    */
    public function scopePending($query)
    {
        return $query->where('status_validasi', 'pending');
    }

    /**
    * Scope a query to only include approved measurements.
    */
    public function scopeApproved($query)
    {
        return $query->where('status_validasi', 'approved');
    }

    /**
    * Scope a query to only include rejected measurements.
    */
    public function scopeRejected($query)
    {
        return $query->where('status_validasi', 'rejected');
    }
}

