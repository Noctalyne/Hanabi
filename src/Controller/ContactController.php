<?php

namespace App\Controller;

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
            // 'last_username' => $lastUsername, 
            // 'error' => $error
        ]);
    }


}
