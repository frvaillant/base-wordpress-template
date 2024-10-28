<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

final class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'acf_is_installed' => function_exists('acf_is_plugin_active') && acf_is_plugin_active()
        ]);
    }
}
