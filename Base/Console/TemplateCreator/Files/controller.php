<?php

namespace App\Controller;

use App\Base\Annotations\Template;
use Symfony\Component\HttpFoundation\Response;

final class ControllerNameController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Response
     *
     * @Template(identifier="%identifier%", name="%name%")
     */
    public function index(): Response
    {
        return $this->render('ControllerName/index.html.twig', [
        ]);
    }
}

