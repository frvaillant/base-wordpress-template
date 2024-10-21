<?php
/**
 * This annotation is made to be used on a Entity class
 * For example : @Entity(name="Video", singular="vidéo", plural="vidéos")
 * It generates a WP CPT (custom post) to be managed in dashboard
 */
namespace App\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class Entity
{

    /**
     * @var string|mixed
     */
    private string $name;
    /**
     * @var string|mixed
     */
    private string $singular;
    /**
     * @var string|mixed
     */
    private string $plural;
    /**
     * @var array|mixed|string[]
     */
    private array $supports = ['title', 'author', 'revisions', 'custom-fields', 'tags', 'page-attributes'];

    /**
     * @throws \Exception
     */
    public function __construct(array $params)
    {
        $this->name     = $params['name'];
        $this->singular = $params['singular'];
        $this->plural   = $params['plural'];

        if(isset($params['supports'])) {
            $this->supports =  $params['supports'];
        }

        $this->secure();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function secure(): void
    {
        if(strtolower($this->name) === 'page')   {
            throw new \Exception('Page name is reserved to wordpress pages');
        }

        if(strtolower($this->name) === 'post')   {
            throw new \Exception('Post name is reserved to wordpress posts');
        }
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
    public function getSingular(): string
    {
        return $this->singular;
    }

    /**
     * @param string $singular
     */
    public function setSingular(string $singular): void
    {
        $this->singular = $singular;
    }

    /**
     * @return string
     */
    public function getPlural(): string
    {
        return $this->plural;
    }

    /**
     * @param string $plural
     */
    public function setPlural(string $plural): void
    {
        $this->plural = $plural;
    }

    /**
     * @return array
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * @param array $supports
     */
    public function setSupports(array $supports): void
    {
        $this->supports = $supports;
    }



}