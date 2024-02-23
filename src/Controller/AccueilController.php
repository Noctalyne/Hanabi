<?php

namespace App\Controller;

use App\Repository\BanniereRepository;
use App\Repository\ProduitsRepository;
use SessionIdInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitsRepository $produitsRepository, BanniereRepository $banniereRepository, SessionInterface $session): Response
    {
        $produit=$produitsRepository->findAll(); // permet de récupérer les info produits
        $carrousel = $banniereRepository->findOneBy(['activated' => 'true']);

        $panier = $session->get('panier');
        $msg = "";
        
        // $this->get('session')->get('panier');
        
        return $this->render('pages/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'msg' => $msg,
            'carrousels' => $carrousel,
            'produits' => $produit,
            'panier' => $panier,
        ]);
    }
}
