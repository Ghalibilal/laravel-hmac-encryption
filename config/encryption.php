<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used to authenticate your encryption.
    | You should set this in your environment (.env) file.
    |
    */

    'encryption_key' => env('ENCRYPTION_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Secret
    |--------------------------------------------------------------------------
    |
    | This secret is used to sign or validate encryption data.
    | Make sure to keep it secure and set it in your .env file.
    |
    */

    'encryption_secret' => env('ENCRYPTION_SECRET'),

];
