<?php

/*
namespace App\Model;
use App\Model\AbstractRepository;
use App\Model\EntityProvider;

final class EntityNameRepository  extends AbstractRepository
{
    public const CLASSNAME = 'App\Entity\EntityName';


    public function __construct()
    {
        parent::__construct();
    }

    public function findAll()
    {
        $args = [
            'post_type'         => 'entitynamelower',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'orderby'			=> 'post_date',
            'order'				=> 'DESC'
        ];
        return $this->provider->provide(query_posts($args), self::CLASSNAME);
    }
}
*/