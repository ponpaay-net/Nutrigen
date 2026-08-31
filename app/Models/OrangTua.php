<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_kk',
        'nik_ayah',
        'nik_ibu',
        'nama_ibu',
        'nama_ayah',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'no_hp_whatsapp',
        'alamat',
        'kecamatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function balitas(): HasMany
    {
        return $this->hasMany(Balita::class);
    }
}
