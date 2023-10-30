<?php

namespace App\Controller;

use App\Repository\BanniereRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitsRepository $produitsRepository, BanniereRepository $banniereRepository): Response
    {
        $produit=$produitsRepository->findAll(); // permet de récupérer les info produits
        $carrousel = $banniereRepository->findOneBy(['activated' => 'true']);
        
        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'carrousels' => $carrousel,
            'produits' => $produit,
        ]);
    }
}
