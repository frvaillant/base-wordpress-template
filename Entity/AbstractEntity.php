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

    /**
     * @var array
     */
    public $fields = [];

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
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->fields[$name] ?? null;
    }

}
