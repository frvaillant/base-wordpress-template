<?php

namespace App\Model;

final class PageRepository extends BaseRepository
{
    public const CLASSNAME = 'App\Entity\Page';

    private int $limit;

    public function __construct()
    {
        parent::__construct();

        $this->limit = $this->getPostLimit();
    }

    public function getPageByTitle(string $title): array
    {
        $args = [
            'post_type' => 'page',
            'title' => $title,
            'numberposts' => 1,
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
        ];
        return $this->provider->provide(query_posts($args), self::CLASSNAME);
    }

}
