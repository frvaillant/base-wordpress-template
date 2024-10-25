<?php


namespace App\Entity;


abstract class AbstractEntity
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $content;

    /**
     * @var bool|\WP_User
     */
    public $post_author;

    /**
     * @var bool|\WP_User
     */
    public $author;

    public $fields = [];

    public function __construct(string | int $id)
    {

        $post   = get_post($id);
        if($post) {
            $this->hydrateEntity($post);

            $this->tags = get_the_tags($post->ID);

            if (function_exists('acf_is_plugin_active') && acf_is_plugin_active()) {
                $fields = get_fields($post->ID);
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

    private function hydrateEntity($array): void
    {
        foreach ($array as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    public function __get(string $name)
    {
        return $this->fields[$name] ?? null;
    }

}
