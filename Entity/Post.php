<?php

namespace App\Entity;

final class Post extends AbstractEntity
{
    public function __construct($postId)
    {
        parent::__construct($postId);
    }
}
