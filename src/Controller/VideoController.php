<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use finfo;

class VideoController 
{
    
    public function __construct(private VideoRepository $repository)
    {}

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

        $video = new Video($url, $titulo);
        if($_FILES['image']['error'] === UPLOAD_ERR_OK){
            
            $fileTempName = uniqid('upload_') . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);

            if(str_starts_with($mimeType, 'image/')){
                
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    __DIR__ . '/../../public/img/uploads/' . $fileTempName
                );
                $video->setFilePath('/img/uploads/' . $fileTempName);
                
            }

        }

        $success = $this->repository->add($video);

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
        
        if($_FILES['image']['error'] === UPLOAD_ERR_OK){
            
            $fileTempName = uniqid('upload_') . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);

            if(str_starts_with($mimeType, 'image/')){
                
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    __DIR__ . '/../../public/img/uploads/' . $fileTempName
                );
                $video->setFilePath('/img/uploads/' . $fileTempName);
                
            }
            
        }

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