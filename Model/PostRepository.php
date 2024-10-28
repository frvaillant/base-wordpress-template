<?php
namespace App\Model;

final class PostRepository extends BaseRepository
{
    public const CLASSNAME = 'App\Entity\Post';

    private int $limit;

    public function __construct()
    {
        parent::__construct();

        $this->limit = $this->getPostLimit();
    }

    /**
     * @return array
     */
    public function getLastPost(): array
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

    /**
     * @param $paged
     * @return array
     */
    public function getAllPosts(int $paged = 1): array
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

    /**
     * @return int|null
     */
    public function countPosts(): ?int
    {
        $args = [
            'post_type'         => 'post',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
        ];
        return count(get_posts($args));
    }
}
