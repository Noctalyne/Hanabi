<?php

namespace App\Controller;

use App\Repository\BanniereRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProduitsRepository $produitsRepository, BanniereRepository $banniereRepository): Response
    {
        $produit=$produitsRepository->findAll(); // permet de récupérer les info produits
        $carrousel = $banniereRepository->findOneBy(['activated' => 'true']);
        
        return $this->render('pages/Boutique/shop.html.twig', [
            'controller_name' => 'ShopController',
            'carrousels' => $carrousel,
            'produits' => $produit,
        ]);
    }
}
