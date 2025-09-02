<?php

namespace App\Controller;

use App\Core\Database;
use App\Core\TokenCsfr;
use App\Models\User;
use App\Repositories\UserRepository;

class LoginController extends Controller
{
    private $userRepository;
    private $csrf;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->csrf = new TokenCsfr(); // Inicializa o CSRF
    }

    public function viewLogin()
    {
        return $this->view('login', [
            'title' => "Login",
            'csrf_field' => $this->csrf->getTokenField() // Passa o campo CSRF para a view
        ]);
    }

    public function login()
    {
        error_log("=== INICIANDO LOGIN ===");
        error_log("Método: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Erro: Não é método POST");
            $_SESSION['login_error'] = "Método inválido";
            header("Location: /hospital/login");
            exit();
        }

        //  VALIDAÇÃO DO TOKEN CSRF 
        $submittedToken = $_POST['token_csrf'] ?? '';
        if (!$this->csrf->validateToken($submittedToken)) {
            error_log("Erro: Token CSRF inválido");
            $_SESSION['login_error'] = "Token de segurança inválido. Tente novamente.";
            header("Location: /hospital/login");
            exit();
        }
        //  FIM VALIDAÇÃO CSRF 

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        error_log("Email: $email, Password: $password");

        if (empty($email) || empty($password)) {
            error_log("Erro: Campos vazios");
            $_SESSION['login_error'] = "Email e senha são obrigatórios";
            header("Location: /hospital/login");
            exit();
        }

        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            error_log("Erro: Email inválido");
            $_SESSION['login_error'] = "Email inválido";
            header("Location: /hospital/login");
            exit();
        }

        try {
            $user = new User();
            $user->setEmail($emailSanitized);
            $user->setPassword($password);

            error_log("Antes de autenticar...");
            $userAuthenticated = $this->userRepository->authenticate($user);
            error_log("Resultado autenticação: " . print_r($userAuthenticated, true));

            if (!$userAuthenticated) {
                error_log("Erro: Autenticação falhou");
                $_SESSION['login_error'] = "Credenciais inválidas";
                header("Location: /hospital/login");
                exit();
            }

            error_log("Usuário autenticado: " . $userAuthenticated['email']);

            $_SESSION['user'] = [
                'id' => $userAuthenticated['id'],
                'email' => $userAuthenticated['email'],
                'name' => $userAuthenticated['name'],
                'role' => $userAuthenticated['role'],
            ];

            error_log("Sessão criada: " . print_r($_SESSION, true));
        
            session_regenerate_id(true);

            $redirectUrl = $userAuthenticated['role'] === 'admin'
                ? '/hospital/dashboard'
                : '/hospital/';

            error_log("Redirecionando para: $redirectUrl");
            header("Location: " . $redirectUrl);
            exit();
        } catch (\Exception $e) {
            error_log("=== EXCEÇÃO DETALHADA ===");
            error_log("Mensagem: " . $e->getMessage());
            error_log("Arquivo: " . $e->getFile());
            error_log("Linha: " . $e->getLine());
            error_log("Trace: " . $e->getTraceAsString());

            $_SESSION['login_error'] = "Erro interno do sistema: " . $e->getMessage();
            header("Location: /hospital/login");
            exit();
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