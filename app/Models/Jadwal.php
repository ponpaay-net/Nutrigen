<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'posyandu_id',
        'kader_id',
        'judul',
        'lokasi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class);
    }
}
