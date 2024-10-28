<?php

namespace App\Entity;

final class Page extends BaseEntity
{
    public function __construct($postId)
    {
        parent::__construct($postId);
    }
}
