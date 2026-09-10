<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting (fallback ke default bila belum ada).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $row = static::query()->where('key', $key)->first();
            return $row?->value ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Simpan/perbarui nilai setting.
     */
    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}