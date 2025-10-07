<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait HtmlRenderTrait 
{

    protected function render(string $templateName, array $context = []): string
    {

        $templatePath = __DIR__ . '/../../views/';
        extract($context);

        ob_start();
        require_once $templatePath . $templateName . '.php';
        $html = ob_get_clean();
        echo $html;
        return $html;


    }

}