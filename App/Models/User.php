<?php

namespace App\Models;

class User
{
    private string $name;
    private string $email;
    private string $password;

    




    public function me()
    {
        return ['name' => "nicolas", 'email' => 'nicolas@email.com'];
    }

    public function getAll()
    {
        return [
            ['name' => 'Nicolas'],
            ['name' => 'Maria'],
            ['name' => 'João']
        ];
    }
    public function UserSession() {}
}
