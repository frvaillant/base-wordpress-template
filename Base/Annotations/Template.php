<?php

/**
 * This annotation is made to be used on a controller method
 * For example : @Template(identifier="videos", name="Page de présentation des videos")
 */

namespace App\Base\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class Template
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $identifier;

    /**
     * @var string
     */
    private string $controller;

    /**
     * @var string
     */
    private string $method;


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->name = $params['name'];
        $this->identifier = $params['identifier'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }


    /**
     * @param string $controller
     */
    public function defineController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function defineControllerMethod(string $method): void
    {
        $this->method = $method;
    }
}
