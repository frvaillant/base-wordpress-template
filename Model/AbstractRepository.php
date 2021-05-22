<?php


namespace App\Model;

use App\Model\EntityProvider;

abstract class AbstractRepository
{

    protected $wpdb;
    /**
     * @var \App\Model\EntityProvider
     */
    protected $provider;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->provider = new EntityProvider();

    }

    protected function getPostLimit()
    {
        return get_option('posts_per_page');
    }

}
