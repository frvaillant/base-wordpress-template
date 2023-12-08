<?php

namespace App\Controller;

use App\Model\PostRepository;
use App\Service\Controller\AbstractController;

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

        return $this->twig->render('faq.html.twig', [
            'posts' => $posts
        ]);

    }

}
