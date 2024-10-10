<?php

namespace App\Controller;

use App\Entity\Page;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PageController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function page(): string
    {
        $page = get_post(get_the_id());
        $page = new Page($page->ID);
        return $this->twig->render('Page/index.html.twig', [
            'page' => $page
        ]);
    }

}
