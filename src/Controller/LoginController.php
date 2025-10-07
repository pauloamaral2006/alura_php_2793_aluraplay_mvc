<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessaTrait;
use Alura\Mvc\Helper\HtmlRenderTrait;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController
{
    
    use FlashMessaTrait, HtmlRenderTrait;

    private PDO $pdo;

    public function __construct(private Engine $templates)
    {    
         
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new PDO("sqlite:$dbPath");    

    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {

        iF($_SESSION['logado'] ?? false === true){

            return new Response(302, [
                'Location' => '/'
            ]);

        }

        return new Response(
            200, 
            body: $this->templates->render('login-form')
        );

    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {

        $queryParams = $request->getParsedBody();
        $email = filter_var($queryParams['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($queryParams['password']);
        $sql = 'SELECT * FROM users WHERE email = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        $correctPassword = password_verify($password, $userData['password'] ?? '');
             
        if(!$correctPassword){
                  
            $this->addErrorMessage('E-mail ou senha inválidos.');
            return new Response(302, [
                'Location' => '/login'
            ]);

        }
        
        if(password_needs_rehash($userData['password'] ?? '', PASSWORD_ARGON2I)){

            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2I));
            $statement->bindValue(2, $email);
            $statement->execute();

        }       
                
        $_SESSION['logado'] = true;
        return new Response(302, [
            'Location' => '/'
        ]);

    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
    
        session_destroy();
        return new Response(
            302, 
            [
                'Location' => '/login'
            ]
        );

    }

}