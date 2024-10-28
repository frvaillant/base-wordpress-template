<?php

namespace App\Entity;

final class Post extends BaseEntity
{
    public function __construct($postId)
    {
        parent::__construct($postId);
    }
}
