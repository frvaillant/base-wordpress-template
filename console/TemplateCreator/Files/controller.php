<?php

/*
namespace App\Controller;

use App\Controller\AbstractController;
use App\Annotations\Template;

class ControllerNameController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->publish(
            $this->twig->render('ControllerName/index.html.twig', [
            ])
        );
    }
}
