<?php
namespace App\Base\Router\Controller;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DebugController extends AbstractController
{

    public function index()
    {
        global $_ROUTER;

        $routes       = $_ROUTER->getRoutesPaths();
        $wordpressUrl = $_ROUTER->getWordpressUrls();

        $data = [
            'router'    => $routes,
            'wordpress' => $wordpressUrl
        ];

        $response = new JsonResponse();
        $response->setData($data);
        return $response->send();
    }

}