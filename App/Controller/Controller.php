<?php

namespace App\Controller;

class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view($view, $data = [])
    {
        // Extrai os dados para variáveis
        extract($data);

        // Inclui a view (caminho correto)
        include __DIR__ . "/../views/$view.php";
    }

    protected function model($model)
    {
        // Namespace completo do model
        $modelClass = "App\\Models\\" . $model;

        // Verifica se a classe existe
        if (class_exists($modelClass)) {
            return new $modelClass();
        }

        throw new \Exception("Model não encontrado: $modelClass");
    }
}
