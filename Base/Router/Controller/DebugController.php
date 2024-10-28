<?php
namespace App\Base\Router\Controller;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DebugController extends BaseController
{

    public function index()
    {
        $_ROUTER = $GLOBALS['_ROUTER'];

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
