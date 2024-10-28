<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *
 */
final class PageController extends BaseController
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
    public function page(): Response
    {
        $page = new Page(get_the_id());
        return $this->render('Page/index.html.twig', [
            'page' => $page,
        ]);
    }
}
