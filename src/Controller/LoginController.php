<?php

namespace Alura\Mvc\Controller;

use PDO;

class LoginController 
{
    
    private PDO $pdo;

    public function __construct()
    {    
         
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new PDO("sqlite:$dbPath");    

    }

    public function index(): void
    {

        IF($_SESSION['logado'] ?? false === true){

            header('Location: /');
            return;

        }

        require_once __DIR__ . '/../../views/login-form.php';

    }

    public function login(): void
    {
    
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $sql = 'SELECT * FROM users WHERE email = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        $correctPassword = password_verify($password, $userData['password'] ?? '');
             
        if($correctPassword){
                           
            if(password_needs_rehash($userData['password'] ?? '', PASSWORD_ARGON2I)){

                $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
                $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2I));
                $statement->bindValue(2, $email);
                $statement->execute();

            }       
                 
            $_SESSION['logado'] = true;
            header('Location: /');

        }else{

            header('Location: /login?success=0');

        }

    }

    public function logout(): void
    {
    
        session_destroy();
        header('Location: /login?');
    }

}