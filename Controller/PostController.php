<?php

namespace App\Controller;

use App\Entity\Post;
use App\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function single()
    {
        $post = get_post(get_the_id());
        $post = new Post($post->ID);
        return $this->twig->render('Post/index.html.twig', [
            'post' => $post
        ])
        ;
    }






}
