<?php

namespace App\Router;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Doctrine\Common\Annotations\AnnotationReader as DocReader;
use Symfony\Component\Routing\Annotation\Route as SiteRoute;

/**
 * This class parses the Controller folder. It gets all *Controller.php files and extracts @Route annotations
 * Finally, il returns a RouteCollection
 */
class RoutesDetector
{
    const ROUTE_CLASS = 'Symfony\Component\Routing\Annotation\Route';

    const EXCLUDED_FILES = [
        '.',
        '..',
        'AbstractController.php'
    ];

    private string $folder;
    private RouteCollection $routes;
    private DocParser $parser;
    private DocReader $reader;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $this->folder = __DIR__ . '/../Controller';
        $this->routes = new RouteCollection();
        $this->parser = new DocParser();
        $this->reader = new AnnotationReader($this->parser);
    }

    /**
     * @param $fileName
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function getReflectionClass($fileName, $folder = null): \ReflectionClass
    {
        $namespace     = $this->getNamespace($folder);
        $className     = $namespace . '\\' . basename($fileName, '.php');

        return new \ReflectionClass($className);
    }

    /**
     * @param $folder
     * @return string
     */
    private function getNamespace($folder): string
    {
        $folderParams  = explode('/Controller', $folder);
        $subFolder     = $folderParams[1];
        $subFolderName = $subFolder !== "" ? 'Controller\\' . str_replace('/', '', $subFolder) : 'Controller';
        return 'App\\' . $subFolderName;
    }


    /**
     * @param null $folder
     * @return RouteCollection
     * @throws \ReflectionException
     *
     * Recursive method in case of sub folders
     */
    public function getRoutes($folderPath = null)
    {
        $folder = $folderPath ?? $this->folder;
        if (is_dir($folder)){
            if ($dh = opendir($folder)){
                while (($file = readdir($dh)) !== false){

                    if(!in_array($file, self::EXCLUDED_FILES) && !strstr($file, '@')) {
                        /**
                         * recursive place
                         */
                        if(is_dir($folder . '/' . $file)) {
                            $subFolder = $folder . '/' . $file;
                            $this->getRoutes($subFolder);
                        }  else {
                            $class = $this->getReflectionClass($file, $folder);
                            foreach ($class->getMethods() as $method) {
                                $this->parseMethod($method);
                            }
                        }
                    }

                }
                closedir($dh);
                return $this->routes;
            }
        }

        throw new \Exception('Impossible to find folder ' . $folder);
    }

    /**
     * @param \ReflectionMethod $method
     * @throws \ReflectionException
     */
    private function parseMethod(\ReflectionMethod $method): void
    {
        $class = $method->getDeclaringClass();
        if ($this->hasValidRoute($method)) {
            $route = $this->getRoute($method);
            $arguments = $this->getMethodArguments($method);
            $arguments['_controller'] = $class->getName() . '::' . $method->getName();
            $this->routes->add($route->getName(), new Route($route->getPath(), $arguments));
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return array
     * @throws \ReflectionException
     */
    private function getMethodArguments(\ReflectionMethod $method): array
    {
        $arguments = [];
        foreach ($method->getParameters() as $parameter) {
            $arguments[$parameter->getName()] = ($parameter->isOptional()) ? $parameter->getDefaultValue() : null;
        }
        return $arguments;
    }

    private function hasValidRoute(\ReflectionMethod $method): bool
    {
        return null !== $this->reader->getMethodAnnotation($method, self::ROUTE_CLASS);
    }

    /**
     * @param \ReflectionMethod $method
     * @return mixed|object|null
     */
    private function getRoute(\ReflectionMethod $method): ?SiteRoute
    {
        return $this->reader->getMethodAnnotation($method, self::ROUTE_CLASS);
    }

}