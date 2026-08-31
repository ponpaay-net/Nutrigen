<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Jejak pengiriman notifikasi (WhatsApp dll.) — TINGGI-02.
 */
class NotificationLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'orang_tua_id',
        'pengukuran_id',
        'channel',
        'status',
        'payload',
        'response_body',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function pengukuran(): BelongsTo
    {
        return $this->belongsTo(Pengukuran::class);
    }
}
