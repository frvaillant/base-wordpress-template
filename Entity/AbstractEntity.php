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

    public function __construct($id)
    {

        $post   = get_post($id);
        $this->hydrateEntity($post);

        $this->tags = get_the_tags($post->ID);

        if (acf_is_plugin_active()) {
            $fields = get_fields($post->ID);
            if($fields) {
                $this->hydrateEntity($fields);
            }
        }

        $this->title = $this->post_title;

        $this->content = $this->post_content;

        $this->post_author = get_user_by('ID', $this->post_author);

    }

    private function hydrateEntity($array) {
        foreach ($array as $key => $value) {
            $this->{$key} = $value;
        }
    }

}
