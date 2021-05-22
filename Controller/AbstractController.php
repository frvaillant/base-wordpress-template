<?php

namespace App\Controller;


abstract class AbstractController
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;
    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        global $twig;
        $this->twig = $twig;
    }


}
