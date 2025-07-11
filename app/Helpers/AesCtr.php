<?php

namespace App\Helpers;

class AesCtr
{
    // Cifra testo con AES-256-CTR (chiave = stringa, lunghezza = 256)
    public static function encrypt($plaintext, $password, $bits = 256)
    {
        $method = "aes-256-ctr";
        $key = substr(hash('SHA256', $password, true), 0, $bits/8);
        $iv = substr(hash('SHA256', 'iv'.$password, true), 0, openssl_cipher_iv_length($method));
        return openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    // Decifra testo cifrato con AES-256-CTR
    public static function decrypt($ciphertext, $password, $bits = 256)
    {
        $method = "aes-256-ctr";
        $key = substr(hash('SHA256', $password, true), 0, $bits/8);
        $iv = substr(hash('SHA256', 'iv'.$password, true), 0, openssl_cipher_iv_length($method));
        return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }
}
