<?php

namespace App\Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct(?int $id = null, ?string $name = null, ?string $email = null, ?string $password = null)
    {
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
        $this->id = $id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
