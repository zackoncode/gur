<?php

namespace App\Core;

class TokenCsfr
{
    private $token;

    public function __construct()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->generateToken();
    }

    public function generateToken()
    {
        if (empty($_SESSION['token_csrf'])) {
            $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
        }
        $this->token = $_SESSION['token_csrf'];
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getTokenField()
    {
        return '<input type="hidden" name="token_csrf" value="'
            . htmlspecialchars($this->token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public function validateToken($submittedToken)
    {
        if (empty($submittedToken) || empty($_SESSION['token_csrf'])) {
            return false;
        }

        return hash_equals($_SESSION['token_csrf'], $submittedToken);
    }

    public function regenerateToken()
    {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
        $this->token = $_SESSION['token_csrf'];
    }
}
