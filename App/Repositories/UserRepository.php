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
    $db = new Database();
    $this->pdo = $db->getConnection();
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

  public function isAdmin($userId)
  {
    $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user && $user['role'] === 'admin';
  }
  public function authenticate(User $user)
  {
    $stmt = $this->pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
    $stmt->execute(['email' => $user->getEmail()]);
    $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dbUser && password_verify($user->getPassword(), $dbUser['password'])) {

      unset($dbUser['password']);
      return $dbUser;
    }

    return false;
  }
}
