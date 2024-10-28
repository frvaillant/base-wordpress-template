<?php


namespace App\Model;

abstract class BaseRepository
{
    protected $wpdb;
    /**
     * @var \App\Model\EntityProvider
     */
    protected $provider;

    public function __construct()
    {
        $wpdb = $GLOBALS['wpdb'];
        $this->wpdb = $wpdb;

        $this->provider = new EntityProvider();
    }

    protected function getPostLimit()
    {
        return get_option('posts_per_page');
    }

}
