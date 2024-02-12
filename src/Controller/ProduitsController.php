<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ClientsRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits')]
class ProduitsController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('pages/admin_view/product/list_products.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageProduit = $form->get('imgProduit')->getData(); // On récupère les données qui composent l’image

            if ($imageProduit) { // Si une image a bien été insérée 

                $originalFilename = pathinfo($imageProduit->getClientOriginalName(), PATHINFO_FILENAME); // On prend le nom de base du fichier
                
                $safeFilename = $slugger->slug($originalFilename);// this is needed to safely include the file name as part of the URL
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageProduit->guessExtension(); // Tente de déplacer le fichier vers le répertoire définit plus tôt
                
                try {
                    $imageProduit->move(
                    $this->getParameter('produit_directory'),
                    $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer le cas en cas d’exception levée (droits insuffisants, stockage insuffisant, ...)
                }

                $produit->setImgProduit($newFilename); // On redéfinit l’image de notre objet pour permettre l’enregistrement du bon nom d’image en BDD
                
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin_view/product/add_new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // #[Route('/{client_id}/{id}', name: 'app_produits_show', methods: ['GET'])] //, 
    #[Route('/{user_id}/show/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(int $user_id, Produits $produit, PanierRepository $panierRepository): Response
    {   
        // $user = 
        // $panier = $panierRepository->findOneBy(['idClient' => $client_id]);

        // $liste = $panier->getListeProduits();
        // $quantite = 0;


        // if ($liste->contains($produit) ){
        //     $quantite = $quantite + 1;
        // }
    //     dd($panier);
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
            // 'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{client_id}/{id}', name: 'app_ajout_panier', methods: ['POST'])]
    // public function ajouterProduit( Request $request, EntityManagerInterface $entityManager, Produits $produits,
    // int $client_id, PanierRepository $panierRepository, ClientsRepository $clientsRepository ): Response
    // {
    //     $panier = $panierRepository->findOneBy(['idClient' => $client_id]);
    //     $client = $clientsRepository->find($client_id);
        
    //     $quantite = 0 ;
    //     $id = $produits->getId();

    //     if ($this->isCsrfTokenValid('ajouter'.$produits->getId(), $request->request->get('_token'))) {

    //         if ($panier === null ) {
    //             $panier = new Panier();
    //             // $montant = $panier->getPrixTotal();

    //             // $panier->setIdClient($client);
    //             $panier->addListeProduit($produits);
    //             $panier->setPrixTotal($produits->getPrixProduit());
    //             $quantite = + 1;
    //             $entityManager->persist($panier);
    //             $entityManager->flush();
    //         }
    //         // elseif (  ) {
    //         //     $quantite = + 1;
    //         // }
    //         else {
    //             $liste = $panier->getListeProduits();

    //             $total = $panier->getPrixTotal();

    //             if ($liste->contains($produits) ){
    //                 // $quantite = $quantite + 1;
                    
    //             }

    //             $panier->addListeProduit($produits);
    //             $panier->setPrixTotal($total + $produits->getPrixProduit());
    //             $entityManager->persist($panier);
    //             $entityManager->flush(); // MAJ de la BDD
    //         }

    //     }

    //     return $this->redirectToRoute("app_produits_show", ['client_id'=> $client_id ,'id'=> $id , 'quantite' => $quantite], Response::HTTP_SEE_OTHER);
    // }




    // #[Route('/{id}/add', name: 'add_cart', methods: ['GET', 'POST'])]
    // public function add_item_to_cart(Request $request, EntityManagerInterface $entityManager) :JsonResponse
    // {
        // $panier = new Panier();
        // $form = $this->createForm(PanierType::class, $panier);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager->persist($panier);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('panier/new.html.twig', [
        //     'panier' => $panier,
        //     'form' => $form,
        // ]);

    //     $session =$request->getSession();


    //     if (!$session->has('panier')) {
    //         $session->set('panier', array());
    //     }

    //     $panier = $session->get('panier');

    //     return $this->json(json_encode($panier));
    // }


    // #[Route('{id}/show/cart', name: 'show_cart', methods: ['GET', 'POST'])]
    // public function show_cart(int $id, Request $request, UserRepository $userRepository , EntityManagerInterface $entityManager) : Response
    // {
    //     // $panier = new Panier();
    //     // $form = $this->createForm(PanierType::class, $panier);
    //     // $form->handleRequest($request);

    //     // if ($form->isSubmitted() && $form->isValid()) {
    //     //     $entityManager->persist($panier);
    //     //     $entityManager->flush();

    //     //     return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     // }

    //     // return $this->render('panier/new.html.twig', [
    //     //     'panier' => $panier,
    //     //     'form' => $form,
    //     // ]);
    //     $user = $userRepository->findBy(['id' => $id ]);
    //     $session =$request->getSession();


    //     // if (!$session->has('panier')) {
    //     //     $session->set('panier', array());
    //     // }

    //     $panier = $session->get('panier');
    //     var_dump($panier);

    //     // return $this->json(json_encode($panier));
    //     // return $this->redirectToRoute('app_panier_show', [
    //     //     'panier' => $panier,
    //     // ], Response::HTTP_SEE_OTHER);

    //     // return $this->redirectToRoute('app_panier_show', ['id' => $id ], Response::HTTP_SEE_OTHER);

    //     return $this->render('panier/_pannier_dd.html.twig', [
    //         'panier' => $panier,
    //     ]);
    // }



}
