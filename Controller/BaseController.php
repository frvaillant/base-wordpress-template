<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class BaseController
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
        $twig = $GLOBALS['twig'];
        $this->twig = $twig;
        $this->response = new Response();
    }

    /**
     * @param string $content
     * @return Response
     *
     * Use this method to return html
     */
    protected function publish(string $content): Response
    {
        $this->response->setContent($content);
        $this->response->setStatusCode(200);
        return $this->response->send();
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    protected function render(string $template, array $params = []): Response
    {
        return $this->publish(
            $this->twig->render($template, $params)
        );
    }

    /**
     * @param string $path
     * @return Response
     *
     * Method use to redirect user
     */
    protected function redirectTo(string $path = '/'): Response
    {
        $response = new RedirectResponse($path);
        return $response->send();
    }

    /**
     * @param array $data
     * @param $code
     * @return JsonResponse
     *
     * Use this method to return json
     */
    protected function json(array $data = [], $code = Response::HTTP_OK): JsonResponse
    {
        $response = new JsonResponse($data, $code);
        return $response->send();
    }

}
