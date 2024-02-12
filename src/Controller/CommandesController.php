<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Produits;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

#[Route('/commandes')]
class CommandesController extends AbstractController
{

    // VOIR TOUTES LES COMMANDES PASSER
    #[Route('/', name: 'app_commandes_index', methods: ['GET'])]
    public function index(CommandesRepository $commandesRepository): Response
    {
        return $this->render('pages/admin_view/commandes/list_commandes.html.twig', [
            'commandes' => $commandesRepository->findAll(),
        ]);
    }


                                    // PAGE AFFICHAGE COMMANDES //


    // Création d'une commande -> puis suppression du panier
    #[Route('/create/{id}/{id_panier}', name: 'app_create_commandes', methods: ['GET', 'POST'])]
    public function create_cmd(int $id, int $id_panier, UserRepository $userRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        $date = new \DateTime();

        $panier = $panierRepository->findOneBy(['id' => $id_panier ]);
        $listProduit = $panier->getListeProduits();
        $totalPanier = $panier->getPrixTotal();

        $commande = new Commandes();

        $commande->setUser($user);
        $commande->setDateCommande($date);

        $prod = [];

        foreach ( $listProduit as $produit) {

            $item = [];

            $nom = $produit->getNomProduit();
            $description = $produit->getDescriptionProduit();
            $img = $produit->getImgProduit();
            $prix = $produit->getPrixProduit();

            array_push($item, $nom, $description, $img, $prix ) ;
            array_push($prod, $item ) ;

            $commande->setListeProduits($prod);

            $entityManager->persist($commande);
        };  

        $commande->setPrixTotal($totalPanier);

        $entityManager->persist($commande);

        $panier->setUser(null);
        
        $entityManager->remove($panier);

        $entityManager->flush();
        

        return $this->redirectToRoute('app_commandes_user_show', ['id' => $id ], Response::HTTP_SEE_OTHER);
    }


    // Voir TOUTES les commandes de l'utilisateur
    #[Route('/{id}', name: 'app_commandes_user_show', methods: ['GET'])]
    public function user_show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $commandes = $user->getListCommandes();

        return $this->render('pages/user_view/cmd/show_all_cmd.html.twig', [
            'commandes' => $commandes ,
        ]);
    }


    // Voir UNE seule commande
    #[Route('/show/{cmd_id}', name: 'app_commandes_show_one', methods: ['GET'])]
    public function cmd_show_one(int $cmd_id ,CommandesRepository $commandesRepository): Response
    {
        $commande = $commandesRepository->findOneBy(['id' => $cmd_id] );

        $produits = $commande->getListeProduits(); 

        return $this->render('pages/user_view/cmd/show_one_cmd.html.twig', [
            'commande' => $commande ,
            'produits' => $produits,
        ]);
    }


    // #[Route('/{id}/delete/{cmd_id}', name: 'app_commandes_delete', methods: ['POST'])]
    // public function delete(int $id, int $cmd_id, Request $request, Commandes $commande, CommandesRepository $commandesRepository , UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    // {
    //     $user = $userRepository->findOneBy(['id' => $id]);
    //     $cmd = $commandesRepository->findOneBy(['id' => $cmd_id]);
    //     if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
    //         $cmd->setUser(null);
            
    //         $entityManager->remove($cmd);

    //         // $entityManager->persist($commande);s

    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_commandes_user_show', ['id' => $id , 'cmd_id'=> $cmd_id ], Response::HTTP_SEE_OTHER);
    // }
}
