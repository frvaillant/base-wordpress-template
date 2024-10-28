<?php

namespace App\Controller;

use App\Base\Annotations\Template;
use App\Entity\Formation;
use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $formation = new Formation(41);

        return $this->render('Page/index.html.twig', [
            'page' => $page,
            'formation' => $formation
        ]);
    }

}
