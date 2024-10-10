<?php

namespace App\Controller;

use App\Entity\Post;
use App\Controller\AbstractController;

class PostController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function single()
    {
        $post = get_post(get_the_id());
        $post = new Post($post->ID);
        return $this->twig->render('Post/index.html.twig', [
            'post' => $post
        ]);
    }

}
