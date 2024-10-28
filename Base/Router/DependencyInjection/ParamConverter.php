<?php

namespace App\Base\Router\DependencyInjection;

final class ParamConverter
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
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function convert(&$arguments): void
    {
        $methodParameters = $this->getMethodParameters();

        foreach ($methodParameters as $index => $methodParameter) {
            $this->processParameter($methodParameter, $index, $arguments);
        }
    }

    /**
     * @return array
     *
     * @throws \ReflectionException
     */
    private function getMethodParameters(): array
    {
        $class = new \ReflectionClass($this->controller[0]::class);
        $method = $class->getMethod($this->controller[1]);
        return $method->getParameters();
    }

    /**
     * @param \ReflectionParameter $methodParameter
     * @param int $index
     * @param array $arguments
     *
     * @return void
     */
    private function processParameter(\ReflectionParameter $methodParameter, int $index, &$arguments): void
    {
        if ($this->isEntityParameter($methodParameter, $index, $arguments)) {
            $this->injectEntity($methodParameter, $index, $arguments);
        } elseif ($this->isServiceParameter($index, $arguments)) {
            $this->injectService($methodParameter, $index, $arguments);
        }
    }

    /**
     * @param $methodParameter
     * @param $index
     * @param $arguments
     *
     * @return bool
     */
    private function isEntityParameter($methodParameter, $index, $arguments): bool
    {
        return $methodParameter->getType()
            && isset($arguments[$index])
            && str_contains($methodParameter->getType()->getName(), 'Entity');
    }

    /**
     * @param $index
     * @param $arguments
     *
     * @return bool
     */
    private function isServiceParameter($index, $arguments): bool
    {
        return !array_key_exists($index, $arguments) || $arguments[$index] === null;
    }

    /**
     * @param $methodParameter
     * @param $index
     * @param $arguments
     *
     * @return void
     */
    private function injectEntity($methodParameter, $index, &$arguments): void
    {
        $entityFqcn = $methodParameter->getType()->getName();
        $arguments[$index] = new $entityFqcn($arguments[$index]);
    }

    /**
     * @param $methodParameter
     * @param $index
     * @param $arguments
     *
     * @return void
     */
    private function injectService($methodParameter, $index, &$arguments): void
    {
        $serviceFqcn = $methodParameter->getType()->getName();
        $arguments[$index] = new $serviceFqcn();
    }
}
