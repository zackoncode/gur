<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database()->getConnection();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['name'], $data['email'], $data['password']) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['name'], $data['email'], $data['password']) : null;
    }

    public function save(User $user): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)"
        );

        $stmt->execute([
            ':name'     => $user->getName(),
            ':email'    => $user->getEmail(),
            ':password' => $user->getPassword()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
