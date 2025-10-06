<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class ApiVideoController 
{
    
    public function __construct(private VideoRepository $repository)
    {}

    public function index(): void
    {
                
        $videoList = array_map(function(Video $video) : array {

            return [
                'url' => $video->url,
                'title' => $video->title,
                'filePath' => '/img/uploads/' . $video->getFilePath()

            ];

        }, $this->repository->all()); 
        echo json_encode($videoList);

    }

    public function create(): void
    {

        $request = file_get_contents('php://input');
        $VideoData = json_decode($request, true);
        $video = new Video($VideoData['url'], $VideoData['title']);
        $this->repository->add($video);

        http_response_code(201);

    }

}