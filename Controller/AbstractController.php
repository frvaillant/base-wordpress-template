<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
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
