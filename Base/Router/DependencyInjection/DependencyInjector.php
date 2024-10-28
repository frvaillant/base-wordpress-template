<?php

namespace App\Base\Router\DependencyInjection;

final class DependencyInjector
{
    private string $controllerName;
    private \ReflectionClass $class;

    public function __construct(string $controllerName)
    {
        $this->controllerName = $controllerName;
        $this->class = new \ReflectionClass($controllerName);
    }

    /**
     * @param $templateInformations
     * @param $parameters -> by reference
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function autoloadDependencies($templateInformations, &$parameters): void
    {
        foreach($this->class->getMethod($templateInformations->getMethod())->getParameters() as $parameter) {
            $className = $parameter?->getType()?->getName();
            $parameters[] = $className ? new $className() : null;
        }
    }
}
