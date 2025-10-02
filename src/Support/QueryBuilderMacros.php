<?php

namespace Ghalibilal\LaravelHmacEncryption\Support;

use Illuminate\Database\Eloquent\Builder;
use Ghalibilal\LaravelHmacEncryption\Facades\Encryption; // ✅ Use your actual facade

class QueryBuilderMacros
{
    public static function register(): void
    {
        // whereEncrypted
        Builder::macro('whereEncrypted', function (string $column, string $operator, string $value) {
            try {
                $encrypted = Encryption::encrypt($value);
                return $this->where($column, $operator, $encrypted);
            } catch (\Throwable $e) {
                return $this;
            }
        });

        // orWhereEncrypted
        Builder::macro('orWhereEncrypted', function (string $column, string $operator, string $value) {
            try {
                $encrypted = Encryption::encrypt($value);
                return $this->orWhere($column, $operator, $encrypted);
            } catch (\Throwable $e) {
                return $this;
            }
        });
    }
}
