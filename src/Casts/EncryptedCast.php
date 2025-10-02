<?php

namespace Ghalibilal\LaravelHmacEncryption\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ghalibilal\LaravelHmacEncryption\Facades\Encryption;

class EncryptedCast implements CastsAttributes
{
    /**
     * Decrypt the attribute from the database.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return Encryption::decrypt($value);
        } catch (\Throwable $e) {
            // Optionally log the error or report
            return $value; // Fallback to raw value if decryption fails
        }
    }

    /**
     * Encrypt the attribute before storing in the database.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return Encryption::encrypt($value);
        } catch (\Throwable $e) {
            // Optionally log the error or report
            return $value; // Fallback to raw value if encryption fails
        }
    }
}
