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
