<?php

namespace App\Entity;

abstract class AbstractEntity
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var bool|\WP_User
     */
    protected $post_author;

    /**
     * @var bool|\WP_User
     */
    protected $author;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @param string|int $id
     */
    public function __construct(string | int $id)
    {

        $post   = get_post($id, ARRAY_A);

        if($post) {
            $postId = $post['ID'];
            $this->hydrateEntity($post);

            $this->tags = get_the_tags($postId);

            if (function_exists('acf_is_plugin_active') && acf_is_plugin_active()) {
                $fields = get_fields($postId);
                if ($fields) {
                    $this->hydrateEntity($fields);
                }
            }

            $this->title = $this->post_title;

            $this->content = $this->post_content;

            $this->post_author = get_user_by('ID', $this->post_author);

            $this->author = $this->post_author;
        }

    }


    /**
     * @param $array
     * @return void
     */
    private function hydrateEntity(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    /**
     * @param string $name
     */
    public function __get(string $name)
    {
        return $this->fields[$name] ?? null;
    }

}
