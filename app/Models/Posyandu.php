<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posyandu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'puskesmas_id',
        'nama',
        'desa_kelurahan',
        'alamat',
    ];

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function kaders(): HasMany
    {
        return $this->hasMany(Kader::class);
    }

    public function balitas(): HasMany
    {
        return $this->hasMany(Balita::class);
    }

    public function pengukurans(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Pengukuran::class, Balita::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}
