<?php

namespace App\Controller;

use App\Core\Database;
use App\Models\User;
use App\Repositories\UserRepository;
use UserController;

class LoginController
{

    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }




    public function login(String $email, string $password)
    {
        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            die("Email invalido");
        };
        $db = new Database();
        $pdo = $db->getConnection();



        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();


        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];

            if ($user['role'] === "admin") {
                header("Location: /hospital/dashboard");
                exit;
            }
            echo "logado";
        } else {
            echo "N logado";
        }
    }
    public function register(string $email, string $name, string $password): array
    {

        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $userRepository = new UserRepository();
            $user = new User(null, $name, $emailSanitized, $passwordHashed);
            $id = $userRepository->save($user);

            return [
                "success" => true,
                "message" => "Usuário cadastrado com sucesso",
                "id" => $id
            ];
        } catch (\PDOException $e) {
            return [
                "success" => false,
                "message" => "Erro ao cadastrar usuário: " . $e->getMessage()
            ];
        }
    }
}
