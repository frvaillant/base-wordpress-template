<?php

namespace App\Router\DependencyInjection;

class ParamConverter
{
    /**
     * @var callable
     */
    private $controller;

    public function __construct(callable $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param $arguments
     * @return void
     * @throws \ReflectionException
     */
    public function convert(&$arguments): void
    {
        $class = new \ReflectionClass($this->controller[0]::class);
        $method = $class->getMethod($this->controller[1]);
        $methodParameters = $method->getParameters();

        /** @var \ReflectionParameter $param */
        foreach ($methodParameters as $index => $methodParameter) {
            $this->injectEntity($methodParameter, $index, $arguments);
            $this->injectService($methodParameter, $index, $arguments);
        }
    }

    /**
     * @param $methodParameter
     * @param $index
     * @param $arguments
     * @return void
     */
    private function injectEntity($methodParameter, $index, &$arguments): void
    {
        if(
            $methodParameter->getType()
            && isset($arguments[$index])
            && str_contains($methodParameter->getType()->getName(), 'Entity')
        ) {
            $entityFqcn = $methodParameter->getType()->getName();
            $arguments[$index] = new $entityFqcn($arguments[$index]);
        }
    }

    /**
     * @param $methodParameter
     * @param $index
     * @param $arguments
     * @return void
     */
    private function injectService($methodParameter, $index, &$arguments): void
    {
        if(!array_key_exists($index, $arguments) || null === $arguments[$index]) {
            $serviceFqcn = $methodParameter->getType()->getName();
            $arguments[$index] = new $serviceFqcn();
        }
    }

}