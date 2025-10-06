<?php

namespace Alura\Mvc\Entity;

class Video
{

    public readonly string $url;
    public readonly int $id;

    public function __construct(string $url, public readonly string $title)
    {
        $this->setUrl($url);
    }
    
    public function setId(int $id): void{

        $this->id = $id;

    }


    public function setUrl(string $url)
    {

        if(filter_var($url, FILTER_VALIDATE_URL) === false){
           // throw new \InvalidArgumentException("URL inválida: $url");
        }
        return $this->url = $url;
    }
}