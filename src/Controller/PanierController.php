<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produits;
// use App\Form\PanierType;
// use App\Repository\ClientsRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
// use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\PanierServices;
use Symfony\Component\Routing\RouterInterface;

#[Route('/panier')]
class PanierController extends AbstractController
{
    // #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    // public function index(PanierRepository $panierRepository): Response
    // {
    //     return $this->render('panier/index.html.twig', [
    //         'paniers' => $panierRepository->findAll(),
    //     ]);
    // }

    private UserRepository $userRepository;
    private ProduitsRepository $produitsRepository;

    public function __construct(UserRepository $userRepository, ProduitsRepository $produitsRepository) {

        $this->userRepository= $userRepository;
        $this->produitsRepository= $produitsRepository;
    }
    public function getRouteTarget(Request $request) {
        $referer = $request->headers->get('referer');
        $parsed_url = parse_url($referer);
        $route = $parsed_url['path'];

        return $route;
    }

   
    #[Route('/{id}/add/cart/{user_id}', name: 'add_cart', methods: ['GET', 'POST'])]
    public function add_item_to_cart(int $id, int $user_id, Request $request, EntityManagerInterface $entityManager) : Response
    {
        
        $produit = $this->produitsRepository->findOneBy(['id' => $id]);
        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        

        if ($this->isCsrfTokenValid('addCart'.$produit->getId(), $request->request->get('_token'))) {

            if ($user->getPanier() == null ) {

                $panier = new Panier();
                $total = $panier->setPrixTotal( $produit->getPrixProduit()) ;

                $user->setPanier($panier);

                $panier->addListeProduit($produit);

                $entityManager->persist($panier);
                $entityManager->persist($user);

                $entityManager->flush();
            }

            elseif ($user->getPanier() != null ) {

                $panier = $user->getPanier();
                $total = $panier->getPrixTotal() + $produit->getPrixProduit() ;

                $panier->setPrixTotal($total);

                $panier->addListeProduit($produit);
                
                $entityManager->persist($panier);

                $entityManager->flush();
            }

        }
        
        return $this->redirect($this->getRouteTarget($request));// return $this->redirect($route);

    }

    
    #[Route('/show/cart/{user_id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(int $user_id, Panier $panier): Response
    {
        $user = $this->userRepository->find($user_id);
        $panier = $user->getPanier();
 
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    


    // #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(PanierType::class, $panier);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('panier/edit.html.twig', [
    //         'panier' => $panier,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('{id}/delete/cart/{user_id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(int $id , int $user_id, Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $referer = $request->headers->get('referer');
        $parsed_url = parse_url($referer);
        $route = $parsed_url['path'];

        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {

            $panier->getListeProduits();

            $panier->setUser(null);
            
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirect($route);
    }

    
}
