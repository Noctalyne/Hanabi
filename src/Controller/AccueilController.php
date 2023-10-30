<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $produit=$produitsRepository->findAll(); // permet de récupérer les info produits
        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'produits' => $produit,
        ]);
    }
}
