<?php

    declare(strict_types=1);

use Alura\Mvc\Controller\ApiVideoController;
use Alura\Mvc\Controller\LoginController;
use Alura\Mvc\Controller\VideoController;

    return [
        'GET|/' => [
            'controller'  => VideoController::class,
            'function'  => 'index'
        ],
        'GET|/novo-video' => [
            'controller'  => VideoController::class,
            'function'  => 'show'
        ],
        'POST|/novo-video' => [
            'controller'  => VideoController::class,
            'function'  => 'create'
        ],
        'GET|/editar-video' => [
            'controller'  => VideoController::class,
            'function'  => 'show'
        ],
        'POST|/editar-video' => [
            'controller'  => VideoController::class,
            'function'  => 'update'
        ],
        'GET|/remover-video' => [
            'controller'  => VideoController::class,
            'function'  => 'delete'
        ],
        'GET|/login' => [
            'controller'  => LoginController::class,
            'function'  => 'index'
        ],
        'POST|/login' => [
            'controller'  => LoginController::class,
            'function'  => 'login'
        ],
        'GET|/logout' => [
            'controller'  => LoginController::class,
            'function'  => 'logout'
        ],

        'GET|/videos-json' => [
            'controller'  => ApiVideoController::class,
            'function'  => 'index'
        ],
        'POST|/videos' => [
            'controller'  => ApiVideoController::class,
            'function'  => 'create'
        ],
    ];
    