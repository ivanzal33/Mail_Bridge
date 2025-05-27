<?php

namespace App\Helpers;

class AesGcmHelper
{
    // Шифрование строки
    public static function encrypt(string $plaintext, string $key): string
    {
        $ivlen = 12; // 12 байт для GCM
        $iv = random_bytes($ivlen);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $plaintext,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        // Вернём base64 для хранения в БД (IV + TAG + CIPHER)
        return base64_encode($iv . $tag . $ciphertext);
    }

    // Дешифрование строки
    public static function decrypt(string $ciphertext_base64, string $key): ?string
    {
        $raw = base64_decode($ciphertext_base64);
        if ($raw === false || strlen($raw) < 28) {
            return null;
        }

        $iv = substr($raw, 0, 12);
        $tag = substr($raw, 12, 16);
        $ciphertext = substr($raw, 28);

        return openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        ) ?: null;
    }
}
