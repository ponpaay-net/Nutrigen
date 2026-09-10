<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kader extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'posyandu_id',
        'nama',
        'no_hp',
    ];

    /**
     * Nomor kontak kader tersimpan terenkripsi at-rest. Akses via Eloquent
     * ($kader->no_hp) tetap mengembalikan nilai asli karena cast men-dekripsi
     * di getter, sehingga fungsi seperti tautan wa.me tidak terganggu.
     */
    protected $casts = [
        'no_hp' => \App\Casts\FallbackEncryptCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pengukurans(): HasMany
    {
        return $this->hasMany(Pengukuran::class);
    }
}
