<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class VideoController 
{
    
    public function __construct(private VideoRepository $repository)
    {    }

    public function index(): void
    {
        
        $videoList = $this->repository->all();

        require_once __DIR__ . '/../../views/video-list.php';

    }
    
    public function show(): void
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $video = null;

        if($id !== false && $id !== NULL) $video = $this->repository->find($id);

        require_once __DIR__ . '/../../views/video-form.php';

    }

    public function create(): void
    {

        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if ($url === false) {
        header('Location: /index.php?sucesso=0');
        exit();
        }
        $titulo = filter_input(INPUT_POST, 'titulo');
        if ($titulo === false) {
        header('Location: /index.php?sucesso=0');
        exit();
        }

        $success = $this->repository->add(new Video($url, $titulo));

        if ($success === false) {
            header('Location: index.php?sucesso=0');
        } else {
            header('Location: index.php?sucesso=1');
        }

    }

    public function update(): void
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if($id === false || $id === NULL){
            header('Location: /index.php?sucesso=0');
            exit();
        }

        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if($url === false || $url === NULL){
            header('Location: /index.php?sucesso=0');
            exit();
        }
        $titulo = filter_input(INPUT_POST, 'titulo');
        if ($titulo === false) {
            header('Location: /index.php?sucesso=0');
            exit();
        }        
        $video = new Video($url, $titulo);
        $video->setId($id);

        $success = $this->repository->update($video);

        if ($success === false) {
            header('Location: index.php?sucesso=0');
        } else {
            header('Location: index.php?sucesso=1');
        }

    }

    public function delete(): void
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if($id === false || $id === NULL){
            header('Location: /index.php?sucesso=0');
            exit();
        }

        $success = $this->repository->remove($id);

        if ($success === false) {
            header('Location: index.php?sucesso=0');
        } else {
            header('Location: index.php?sucesso=1');
        }

    }
}