<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;

class FallbackEncryptCast implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            // decrypt($value, false) prevents it from trying to unserialize the plain text
            return decrypt($value, false);
        } catch (\Throwable $e) {
            // Catch DecryptException or unserialize errors
            return $value;
        }
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        // encrypt($value, false) encrypts the string without serializing it
        return encrypt($value, false);
    }
}
