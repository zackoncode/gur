<?php

namespace App\Session;

use App\Models\User;

class Session
{
    private User $user;

    public function sessionCriptografy($plainText)
    {
        $key = random_bytes(32);
        $method = "AES-256-CBC";

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)); //gera bytes aleatiorios

        $encrypted = openssl_encrypt($plainText, $method, $key, 0, $iv);
    
        $decrypted = openssl_decrypt($encrypted, $method, $key, 0, $iv);
    }
}
