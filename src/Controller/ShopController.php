<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $produit=$produitsRepository->findAll(); // permet de récupérer les info produits
        return $this->render('shop/shop.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $produit,
        ]);
    }
}
