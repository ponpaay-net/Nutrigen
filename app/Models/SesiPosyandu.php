<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPosyandu extends Model
{
    use HasFactory;

    protected $fillable = [
        'posyandu_id',
        'kader_id',
        'bulan',
        'tahun',
        'total_sasaran',
        'total_terukur',
        'total_absen',
        'persentase_kehadiran',
        'catatan_kader',
        'status',
        'tanggal_kirim'
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function kader()
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    public function pengukurans()
    {
        return $this->hasMany(Pengukuran::class);
    }
}
