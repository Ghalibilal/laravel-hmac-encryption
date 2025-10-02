<?php

namespace Ghalibilal\LaravelHmacEncryption;

class EncryptionService
{
    protected string $key;
    protected string $iv;

    public function __construct()
    {
        // Extract the base64-encoded key and IV from config and decode them
        $encodedKey = config('encryption.encryption_key');
        $encodedIV  = config('encryption.encryption_secret');

        if (!is_string($encodedKey) || !is_string($encodedIV)) {
            throw new \RuntimeException('Encryption key or IV not set in config.');
        }

        $this->key = base64_decode(substr($encodedKey, 7), true);
        $this->iv  = base64_decode(substr($encodedIV, 7), true);

        if ($this->key === false || $this->iv === false) {
            throw new \RuntimeException('Invalid base64 encryption key or IV.');
        }
    }

    public function encrypt(string $value): string
    {
        if (empty($value)) {
            return $value;
        }

        if ($this->isEncrypted($value)) {
            return $value;
        }

        $ciphertext = openssl_encrypt(
            $value,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        if ($ciphertext === false) {
            throw new \RuntimeException('Encryption failed.');
        }

        $hmac = hash_hmac('sha256', $ciphertext, $this->key, true);

        return 'enc:' . base64_encode($hmac . $ciphertext);
    }

    public function decrypt(string $encrypted): string
    {
        if (!$this->isEncrypted($encrypted)) {
            return $encrypted;
        }

        $encoded = substr($encrypted, 4); // Strip "enc:"
        $data = base64_decode($encoded, true);

        if ($data === false || strlen($data) <= 32) {
            return $encrypted;
        }

        $hmac = substr($data, 0, 32);
        $ciphertext = substr($data, 32);

        $calculatedHmac = hash_hmac('sha256', $ciphertext, $this->key, true);

        if (!hash_equals($hmac, $calculatedHmac)) {
            return $encrypted;
        }

        $decrypted = openssl_decrypt(
            $ciphertext,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        return $decrypted !== false ? $decrypted : $encrypted;
    }

    protected function isEncrypted(string $value): bool
    {
        return str_starts_with($value, 'enc:');
    }
}
