<?php

namespace App\Entity;

abstract class BaseEntity
{
    protected string $title;
    protected string $content;
    protected \WP_User|bool $post_author;
    protected \WP_User|bool $author;
    protected array $fields = [];

    public function __construct(string|int $id)
    {
        $post = get_post($id, ARRAY_A);
        if ($post) {
            $this->initializePostAttributes($post);
            $this->initializeAdditionalFields($post['ID']);
        }
    }

    private function initializePostAttributes(array $post): void
    {
        $this->hydrateEntity($post);
        $this->title = $post['post_title'] ?? '';
        $this->content = $post['post_content'] ?? '';
        $this->post_author = get_user_by('ID', $post['post_author'] ?? null);
        $this->author = $this->post_author;
    }

    private function initializeAdditionalFields(int $postId): void
    {
        $this->fields['tags'] = get_the_tags($postId) ?? [];
        if ($this->isAcfActive()) {
            $acfFields = get_fields($postId);
            if($acfFields) {
                $this->hydrateEntity($acfFields);
            }
        }
    }

    private function isAcfActive(): bool
    {
        return function_exists('acf_is_plugin_active') && acf_is_plugin_active();
    }

    private function hydrateEntity(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * Allows twig accessing properties doing {{ entity.property }}
     * This avoids having to create getters for each field
     */
    public function __call(string $name, array $arguments)
    {
        return $this->{$name} ?? $this->fields[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * Allow PHP code accessing properties doing $entity->propertyName
     * This avoids having to declare each field as public property in your entity
     */
    public function __get(string $name)
    {
        return $this->{$name} ?? $this->fields[$name] ?? null;
    }
}
