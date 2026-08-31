<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balita extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'orang_tua_id',
        'posyandu_id',
        'nik',
        'no_bpjs',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'berat_lahir',
        'panjang_lahir',
        'lingkar_kepala_lahir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_lahir' => 'float',
        'panjang_lahir' => 'float',
        'lingkar_kepala_lahir' => 'float',
    ];

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pengukurans(): HasMany
    {
        return $this->hasMany(Pengukuran::class);
    }

    public function latestPengukuran(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Pengukuran::class)->latestOfMany('tanggal_ukur');
    }
}
