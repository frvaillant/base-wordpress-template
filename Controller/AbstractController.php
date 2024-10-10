<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;
    /**
     * @var array
     */
    private $config;

    /**
     * @var Response
     */
    protected Response $response;

    public function __construct()
    {
        global $twig;
        $this->twig = $twig;
        $this->response = new Response();

    }

    protected function publish(string $content): Response
    {
        $this->response->setContent($content);
        $this->response->setStatusCode(200);
        return $this->response->send();
    }

    protected function redirectTo(string $path = '/'): Response
    {
        global $_ROUTER;
        $path = $_ROUTER->getRoutesPaths()[$path] ?? $path;
        $response = new RedirectResponse($path);
        return $response->send();
    }


}
