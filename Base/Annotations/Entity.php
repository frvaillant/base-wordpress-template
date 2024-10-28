<?php

/**
 * This annotation is made to be used on an Entity class
 * For example : @Entity(name="Video", singular="vidéo", plural="vidéos")
 * It generates a WP CPT (custom post) to be managed in dashboard
 */

namespace App\Base\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class Entity
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $singular;

    /**
     * @var string
     */
    private string $plural;

    /**
     * @var array
     */
    private array $supports = [
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats',
        'menu_order',
        'sticky',
    ];

    private array $exclude = [];

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

        if(isset($params['exclude'])) {
            $this->exclude =  $params['exclude'];
        }

        $this->removeExcludedSupports();

        $this->secure();
    }

    /**
     * @return void
     */
    public function removeExcludedSupports(): void
    {
        foreach ($this->exclude as $excluded) {
            $key = array_search($excluded, $this->supports);
            if($key) {
                unset($this->supports[$key]);
            }
        }
    }

    /**
     * @return void
     *
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
     * @return string
     */
    public function getSingular(): string
    {
        return $this->singular;
    }

    /**
     * @return string
     */
    public function getPlural(): string
    {
        return $this->plural;
    }

    /**
     * @return array
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * @return array
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }
}
