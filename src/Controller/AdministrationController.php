<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use App\Repository\FormulaireDemandeProduitRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    // Cette route est juste ici pour récupérer tout les élément sur la même page
    #[Route('/administration', name: 'app_administration')]
    public function index(
        UserRepository  $userRepository, 
        ProduitsRepository $produitsRepository,
        FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository,
        CommandesRepository $commandesRepository,
        ): Response
    {
        return $this->render('pages/administration.html.twig', [

            'users' => $userRepository->findAll(),
            'produits' => $produitsRepository->findAll(),
            'formulaire_demande_produits' => $formulaireDemandeProduitRepository->findAll(),
            'commandes' => $commandesRepository->findAll(),

            'controller_name' => 'AdministrationController',
        ]);
    }
}
