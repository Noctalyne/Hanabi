<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\User;
use App\Repository\FormulaireDemandeProduitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(AuthenticationUtils $authenticationUtils,FormulaireDemandeProduitRepository $formulaires, UserRepository $userRepository ): Response
    {
        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/contact/{id}', name: 'app_contact_client')]
    public function contactClient( int $id, AuthenticationUtils $authenticationUtils,FormulaireDemandeProduitRepository $formulaires, UserRepository $userRepository ): Response
    {
        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContactController',
            'formulaires' => $formulaires->findBy(['user' => $id ]),
        ]);
    }


}
