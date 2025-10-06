<?php 

    declare(strict_types=1);

    session_start();
    if (isset($_SESSION['logado'])) {
        $originalInfo = $_SESSION['logado'];
        unset($_SESSION['logado']);
        session_regenerate_id();
        $_SESSION['logado'] = $originalInfo;
    }

    use Alura\Mvc\Repository\VideoRepository;

    require_once __DIR__ . '/../vendor/autoload.php';
            
    $dbPath = __DIR__ . '/../banco.sqlite';
    $pdo = new PDO("sqlite:$dbPath");    
    $repository = new VideoRepository($pdo);    

    $routes = require_once __DIR__ . '/../config/routes.php';

    $pathInfo = $_SERVER['PATH_INFO'] ?? '/';
    $httpMethod = $_SERVER['REQUEST_METHOD'];

    $isLoginRoute = $pathInfo === '/login';
    if((!array_key_exists('logado', $_SESSION) || $_SESSION['logado'] !== true) && !$isLoginRoute){
        header('Location: /login');
        return;
    }

    $key = "$httpMethod|$pathInfo";

    if(!array_key_exists($key, $routes)){
        http_response_code(404);
        exit();
    }

    $controllerClass = $routes[$key]['controller'] ?? null;
    $controllerMethod = $routes[$key]['function'] ?? null;
    $controller = new $controllerClass($repository);

    $controller->{$controllerMethod}();
