<?php

use DI\Container;
use League\Plates\Engine;

    $dbPath = __DIR__ . '/../banco.sqlite';
    $container = new Container([
        PDO::class => function() use ($dbPath) {                
            return new PDO("sqlite:$dbPath");
        },
        //PDO::class => \DI\create(PDO::class)->constructor("sqlite:$dbPath"),
        Engine::class => function() {
            return new Engine(__DIR__ . '/../views/');
        },
    ]);

    return $container;