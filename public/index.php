<?php 

    declare(strict_types=1);
    
    use Psr\Container\ContainerInterface;
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

   if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION['logado'])) {
        $originalInfo = $_SESSION['logado'];
        unset($_SESSION['logado']);
        session_regenerate_id();
        $_SESSION['logado'] = $originalInfo;
    }

    

    require_once __DIR__ . '/../vendor/autoload.php';
    /***@var ContainerInterface */
    $diCOntainer = require_once __DIR__ . '/../config/dependencies.php';
            
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
    $controller = $diCOntainer->get($controllerClass);

    // Instanciate ANY PSR-17 factory implementations. Here is nyholm/psr7 as an example
    $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

    $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $request = $creator->fromGlobals();

    $response = $controller->{$controllerMethod}($request);

    http_response_code($response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {  
            header (sprintf('%s: %s', $name, $value), false);
        }
    }

    echo $response->getBody();
