<?php

namespace App\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{

    private Request $request;
    private UrlMatcher $matcher;
    private RouteCollection $routes;
    private RoutesDetector $routesDetector;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->routesDetector = new RoutesDetector();
        $this->initRoutes();
        $this->makeUrlMatcher();
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
     * @return RouteCollection
     * @throws \ReflectionException
     */
    private function initRoutes(): void
    {
        $this->routes = $this->routesDetector->getRoutes();
    }

    public function addRoute(string $routeId, Route $route)
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

    public function getMatch()
    {
        try {
            $this->request->attributes->add($this->matcher->match($this->getPath()));
            return $this->matcher->match($this->getPath());
        } catch (ResourceNotFoundException $e) {
            return false;
        }
    }


    public function execute()
    {
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->getRequest());
        $argumentsResolver = new ArgumentResolver();
        $arguments = $argumentsResolver->getArguments($this->getRequest(), $controller);
        call_user_func_array($controller, $arguments);
    }

}