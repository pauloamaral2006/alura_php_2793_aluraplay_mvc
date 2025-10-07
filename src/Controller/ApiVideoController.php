<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiVideoController 
{
    
    public function __construct(private VideoRepository $repository)
    {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
                
        $videoList = array_map(function(Video $video) : array {

            return [
                'url' => $video->url,
                'title' => $video->title,
                'filePath' => '/img/uploads/' . $video->getFilePath()

            ];

        }, $this->repository->all()); 
        
        return new Response(
            200, 
            [
                'content-type' => 'application/json'
            ], 
            json_encode($videoList)
        );

    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $request = $request->getBody()->getContents();
        $VideoData = json_decode($request, true);
        $video = new Video($VideoData['url'], $VideoData['title']);
        $this->repository->add($video);

        return new Response(201);

    }

}