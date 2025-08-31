<?php



ini_set('display_errors', 1);
error_reporting(E_ALL);

use App\Controller\LoginController;
// Autoloader
spl_autoload_register(function ($className) {
    // Converte namespace para caminho de arquivo
    $file = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


session_start();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basepath = "/hospital";

if (strpos($uri, $basepath) === 0) {
    $uri = substr($uri, strlen($basepath));
}
$uri = $uri === '' ? '/' : $uri;
$routes = [
    "/" => ["App\Controller\HomeController", "index"],
    "/user/register" => ["App\Controller\UserController", "register"],
    "/user/login" => ["App\Controller\UserController", "login"],
    "/dashboard" => ["App\Controller\UserController", "isAdmin"],
];


if (!isset($routes[$uri])) {
    http_response_code(404);
    echo "Página não encontrada";
    exit;
}

[$controllerClass, $action] = $routes[$uri];

try {
    if (!class_exists($controllerClass)) {
        throw new Exception("Controller $controllerClass não encontrado");
    }

    $controller = new $controllerClass();

    echo $_SESSION['user']['name'];


    if (!method_exists($controller, $action)) {
        throw new Exception("Ação $action não encontrada no controller $controllerClass");
    }

    $controller->$action();
} catch (Error | Exception $e) {
    echo "Erro: " . $e->getMessage();
    echo "<br>Arquivo: " . $e->getFile();
    echo "<br>Linha: " . $e->getLine();
}
