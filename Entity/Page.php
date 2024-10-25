<?php

namespace App\Entity;

final class Page extends AbstractEntity
{
    public function __construct($postId)
    {
        parent::__construct($postId);
    }
}
