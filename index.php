<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DEBUG: Mostrar informações da requisição
// echo "<h3>DEBUG:</h3>";
// echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
// echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
require_once __DIR__ . "/App/Controller/Controller.php";
// Autoloader
spl_autoload_register(function ($className) {
    // Converte namespace para caminho de arquivo
    $file = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    // echo "Tentando carregar: $file<br>";

    if (file_exists($file)) {
        require_once $file;
        // echo "Arquivo carregado: $file<br>";
    } else {
        // echo " Arquivo NÃO encontrado: $file<br>";
    }
});

require_once "./App/Core/Routes.php";

// CORREÇÃO IMPORTANTE: Remover o path base se necessário
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remove o diretório base se estiver em subpasta
$basePath = dirname($scriptName);
if ($basePath !== '/') {
    $requestUri = str_replace($basePath, '', $requestUri);
}

$uri = parse_url($requestUri, PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

// echo "URI processada: $uri<br>";
// echo "Rotas definidas: " . json_encode(array_keys($routes)) . "<br>";

$matched = false;

foreach ($routes as $route => $handler) {
    // echo "Testando rota: $route -> $handler<br>";

    // Converte a rota para regex
    $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^\/]+)', $route);
    $pattern = "#^" . $pattern . "$#";

    // echo "Pattern: $pattern<br>";

    if (preg_match($pattern, $uri, $matches)) {
        // echo " ROTA ENCONTRADA<br>";
        // echo "Matches: " . json_encode($matches) . "<br>";

        $matched = true;

        // Separa controller e método
        list($controllerClass, $method) = explode('@', $handler);

        // echo "Controller: $controllerClass, Method: $method<br>";

        // Filtra apenas os parâmetros nomeados
        $params = array_filter($matches, function ($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);

        // Carrega e executa o controller
        if (class_exists($controllerClass)) {
            // echo " Classe existe<br>";
            $controller = new $controllerClass();

            if (method_exists($controller, $method)) {
                // echo " Método existe<br>";
                call_user_func_array([$controller, $method], $params);
            } else {
                http_response_code(500);
                echo "Método $method não encontrado no controller $controllerClass";
            }
        } else {
            http_response_code(500);
            echo "Controller $controllerClass não encontrado";
        }

        break;
    } else {
        // echo " Não match<br>";
    }
    // echo "<hr>";
}

// Se nenhuma rota foi encontrada
if (!$matched) {
    http_response_code(404);
    echo "<h2>Página não encontrada - 404</h2>";
    echo "URI solicitada: $uri<br>";
    echo "Nenhuma das rotas definidas correspondeu.";
}
