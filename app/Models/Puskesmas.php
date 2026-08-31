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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posyandus(): HasMany
    {
        return $this->hasMany(Posyandu::class);
    }
}
