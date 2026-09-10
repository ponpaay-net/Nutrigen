<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Label manusiawi untuk aksi.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Menambahkan',
            'update' => 'Mengubah',
            'delete' => 'Menghapus',
            'login'  => 'Masuk',
            default  => ucfirst((string) $this->action),
        };
    }

    /**
     * Catat satu baris audit log (dipanggil dari controller).
     */
    public static function record(string $action, ?string $modelType = null, ?int $modelId = null, ?string $description = null): self
    {
        try {
            return static::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'model_type'  => $modelType,
                'model_id'    => $modelId,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Audit log tidak boleh memblokir alur utama.
            return new static();
        }
    }
}