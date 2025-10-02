<?php

namespace Ghalibilal\LaravelHmacEncryption\Facades;

use Illuminate\Support\Facades\Facade;

class Encryption extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'encryption-service';
    }
}
