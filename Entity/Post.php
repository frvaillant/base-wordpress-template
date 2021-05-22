<?php

namespace App\Entity;

use App\Entity\AbstractEntity;

class Post extends AbstractEntity
{

    public function __construct($postId)
    {
        parent::__construct($postId);
    }

}
