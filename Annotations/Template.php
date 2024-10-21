<?php
/**
 * This annotation is made to be used on a controller method
 * For example : @Template(identifier="videos", name="Page de présenttaion des videos")
 */
namespace App\Annotations;

use Doctrine\Common\Annotations\Annotation;


/**
 * @Annotation
 */
class Template
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
     * @param string $identifier
     * @param string $name
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
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
    public function setController(string $controller): void
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
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }





}