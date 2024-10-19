<?php

namespace App\Entity;

use App\Annotations\Entity;
use App\Entity\AbstractEntity;

class Page extends AbstractEntity
{

    public function __construct($postId)
    {
        parent::__construct($postId);
    }

}
