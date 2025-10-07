<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessaTrait;
use Alura\Mvc\Helper\HtmlRenderTrait;
use Alura\Mvc\Repository\VideoRepository;
use finfo;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VideoController 
{
    
    use FlashMessaTrait, HtmlRenderTrait;

    public function __construct(private Engine $templates, private VideoRepository $repository)
    {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
                
        $videoList = $this->repository->all();

        return new Response(
            200, 
            body: $this->templates->render(
                'video-list', 
                ['videoList' => $videoList]
            )
        );

    }
    
    public function show(ServerRequestInterface $request): ResponseInterface
    {

        $queryParams = $request->getQueryParams();
        
        $id = filter_var($queryParams['id'] ?? null, FILTER_VALIDATE_INT);
        $video = null;

        if($id !== false && $id !== NULL) $video = $this->repository->find($id);
        
        return new Response(
            200, 
            body:$this->templates->render(
                'video-form',
                ['video' => $video]
            )
        );
        
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {

        $queryParams = $request->getParsedBody();
        $url = filter_var($queryParams['url'], FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('URL inválida.');
            return new Response(404, [
                'Location' => '/editar-video'
            ]);
        }
        $titulo = filter_var($queryParams['titulo']);
        if ($titulo === false) {
            $this->addErrorMessage('Título inválido.');
            return new Response(404, [
                'Location' => '/editar-video'
            ]);
        }      

        $video = new Video($url, $titulo);
        $files = $request->getUploadedFiles();
        /** @var UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'];
        if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpFile = $uploadedImage->getStream()->getMetadata('uri');
            $mimeType = $finfo->file($tmpFile);

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
                $uploadedImage->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
                $video->setFilePath($safeFileName);
            }
        }

        $success = $this->repository->add($video);

        if ($success === false) {
            $this->addErrorMessage('Erro ao cadastrar vídeo.');
            return new Response(404, [
                'Location' => '/novo-video'
            ]);
        } else {
            return new Response(201, [
                'Location' => '/'
            ]);
        }

    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {

        $queryParams = $request->getParsedBody();  
        
        $id = filter_var($request->getQueryParams()['id'] ?? null, FILTER_VALIDATE_INT);
        if($id === false || $id === NULL){            
            $this->addErrorMessage('ID inválido.');
            return new Response(302, [
                'Location' => '/editar-video'
            ]);
        }
        $url = filter_var($queryParams['url'], FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('URL inválida.');
            return new Response(302, [
                'Location' => '/editar-video'
            ]);
        }
        $titulo = filter_var($queryParams['titulo']);
        if ($titulo === false) {
            $this->addErrorMessage('Título inválido.');
            return new Response(302, [
                'Location' => '/editar-video'
            ]);
        }      
        $video = new Video($url, $titulo);
        $video->setId($id);
        
        $files = $request->getUploadedFiles();
        /** @var UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'];
        if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpFile = $uploadedImage->getStream()->getMetadata('uri');
            $mimeType = $finfo->file($tmpFile);

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
                $uploadedImage->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
                $video->setFilePath($safeFileName);
            }
        }

        $success = $this->repository->update($video);
        
        if ($success === false) {
            $this->addErrorMessage('Erro ao alterar vídeo.');
            return new Response(302, [
                'Location' => '/editar-video'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/'
            ]);
        }

    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {

        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        if($id === false || $id === NULL){
            $this->addErrorMessage('ID inválido.');
            return new Response(302, [
                'Location' => '/'
            ]);
        }

        $success = $this->repository->remove($id);

        if ($success === false) {
            $this->addErrorMessage('Erro ao remover vídeo.');
            return new Response(302, [
                'Location' => '/'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/'
            ]);
        }

    }
}