<?php


namespace App\Controller\Test;

use App\Annotations\Template;
use App\Controller\AbstractController;
use App\Entity\Page;

class AppointmentController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Template(identifier="rdv", name="Page de test des rendez-vous")
     */
    public function index()
    {

        $page = new Page(get_the_ID());
        return $this->publish(
            $this->twig->render('Appointment/index.html.twig', [
                'page' => $page
            ])
        );
    }
}
