<?php
namespace App\Model;
use App\Model\EntityProvider;

class PostRepository extends AbstractRepository
{
    const CLASSNAME = 'App\Entity\Post';

    private $limit;

    public function __construct()
    {
        parent::__construct();

        $this->limit = $this->getPostLimit();
    }

    public function getLastPost()
    {
        $args = [
            'post_type'         => 'post',
            'posts_per_page'    => $this->limit,
            'post_status'       => 'publish',
            'orderby'			=> 'post_date',
            'order'				=> 'DESC'
        ];
        return $this->provider->provide(query_posts($args), self::CLASSNAME);
    }

    public function getAllPosts($paged = 1)
    {
        $args = [
            'post_type'         => 'post',
            'posts_per_page'    => $this->limit,
            'post_status'       => 'publish',
            'paged'             => $paged,
            'orderby'			=> 'post_date',
            'order'				=> 'DESC'
        ];
        return $this->provider->provide(query_posts($args), self::CLASSNAME);
    }

    public function countPosts()
    {
        $args = [
            'post_type'         => 'post',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
        ];
        return count(get_posts($args));
    }

}
