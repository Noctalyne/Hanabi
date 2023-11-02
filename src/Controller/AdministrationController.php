<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    // Cette route est juste ici pour récupérer tout les élément sur la même page
    #[Route('/administration', name: 'app_administration')]
    public function index(): Response
    {
        return $this->render('administration/administration.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
}
