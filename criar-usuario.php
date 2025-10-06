<?php 

    declare(strict_types=1);
            
    $dbPath = __DIR__ . '/banco.sqlite';
    $pdo = new PDO("sqlite:$dbPath");  

    $email = $argv[1];
    $password = $argv[2];
    $hash = password_hash($password, PASSWORD_ARGON2ID);

    $sql = 'INSERT INTO users (email, password) VALUES (?, ?)';
    $statment = $pdo->prepare($sql);
    $statment->bindValue(1, $email);
    $statment->bindValue(2, $hash);
    $statment->execute();
    