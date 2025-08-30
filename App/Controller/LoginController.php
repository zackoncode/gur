<?php

namespace App\Controller;

use App\Core\Database;

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
                'id' =>
                $user['id'],
                'name' => $user['name'],
                'role' => "user",
            ];
            echo "logado";
        } else {
            echo "N logado";
        }
    }
    public function register($email, $name, $password)
    {
        //$name = $_POST['name'];
        // $email = $_POST['email'];
        //$password = $_POST['password'];

        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios(name,email,password) VALUES (:name,:email,:password)");
            $stmt->execute([':name' => $name, ':email' => $emailSanitized, ':password' => $passwordHashed]);
            return ["success" => true, "message" => "Cadastro realizado"];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
