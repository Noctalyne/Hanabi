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
        return $this->render('pages/Administration/produits/list_products.html.twig', [
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

        return $this->render('pages/Administration/produits/gestion/add_new.html.twig', [
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
        return $this->render('pages/Boutique/produits/produit.html.twig', [
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

        return $this->render('pages/Administration/produits/gestion//edit.html.twig', [
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


}
