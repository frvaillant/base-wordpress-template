<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Model\PostRepository;

class HomeController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $repository = new PostRepository();
        $posts = $repository->getLastPost();

        return $this->twig->render('index.html.twig', [
            'posts' => $posts
        ]);

    }

}
