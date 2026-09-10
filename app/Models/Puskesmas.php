<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puskesmas extends Model
{
    use HasFactory;

    protected $table = 'puskesmas';

    protected $fillable = [
        'user_id',
        'nama',
        'kode_faskes',
        'alamat',
        'kepala_puskesmas',
        'no_telp',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
    ];

    /**
     * Nomor telepon puskesmas terenkripsi di dalam basis data. Akses via
     * Eloquent ($puskesmas->no_telp) men-dekripsi otomatis di getter.
     */
    protected $casts = [
        'no_telp' => \App\Casts\FallbackEncryptCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posyandus(): HasMany
    {
        return $this->hasMany(Posyandu::class);
    }

    public function balitas(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Balita::class, Posyandu::class);
    }
}
