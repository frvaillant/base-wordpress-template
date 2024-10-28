<?php

namespace App\Base\Router;

use App\Base\Router\DependencyInjection\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Router
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var UrlMatcher
     */
    private UrlMatcher $matcher;

    /**
     * @var RouteCollection
     */
    private RouteCollection $routes;

    /**
     * @var RoutesCollector
     */
    private RoutesCollector $routesCollector;

    /**
     * @var array
     */
    private array $routesPaths = [];

    /**
     * @var array
     */
    private array $wordpressUrls = [];

    /**
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->routesCollector = new RoutesCollector();
        $this->initRoutes();
        $this->makeUrlMatcher();
        $this->addDebugRoute();
        $this->wordpressUrls = $this->routesCollector->getWordpressUrls();
    }

    /**
     * @return void
     */
    private function addDebugRoute(): void
    {
        $this->addRoute('debug_router', new Route('/debug/router', [
            '_controller' => 'App\Router\Controller\DebugController::index'
        ]));
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getRoutesPaths(): array
    {
        return $this->routesPaths;
    }

    /**
     * @return array
     */
    public function getWordpressUrls(): array
    {
        return $this->wordpressUrls;
    }


    /**
     * @return RouteCollection
     *
     * @throws \ReflectionException
     */
    private function initRoutes(): void
    {
        $this->routes = $this->routesCollector->getRoutes();
        $this->routesPaths = $this->routesCollector->getRoutesPaths();
    }

    /**
     * @param string $routeId
     * @param Route $route
     *
     * @return void
     */
    public function addRoute(string $routeId, Route $route): void
    {
        $this->routes->add($routeId, $route);
    }

    /**
     * @return UrlMatcher
     */
    private function makeUrlMatcher(): void
    {
        $context = new RequestContext();
        $context->fromRequest($this->request);
        $this->matcher = new UrlMatcher($this->getRoutes(), $context);
    }

    /**
     * @return bool|array
     */
    public function match(): bool|array
    {
        try {
            $this->request->attributes->add($this->matcher->match($this->getPath()));
            return $this->matcher->match($this->getPath());
        } catch (ResourceNotFoundException $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function methodIsAllowed(): bool
    {
        $route = $this->matcher->match($this->getPath());
        $route = $route['_route'] ?? null;

        if(
            $route
            && count($this->routes->get($route)->getMethods()) > 0
            && ! in_array($this->request->getMethod(), $this->routes->get($route)->getMethods())
        ) {
            throw new MethodNotAllowedException($this->routes->get($route)->getMethods(), 'Method ' . $this->request->getMethod() . ' not allowed on route ' . $route);
        }
        return true;
    }


    /**
     * @throws \ReflectionException
     */
    public function execute(): void
    {
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->getRequest());

        $argumentsResolver = new ArgumentResolver();
        $arguments = $argumentsResolver->getArguments($this->getRequest(), $controller);

        $paramConverter = new ParamConverter($controller);
        $paramConverter->convert($arguments);

        call_user_func_array($controller, $arguments);
    }
}
