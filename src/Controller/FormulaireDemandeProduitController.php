<?php

namespace App\Controller;

use App\Entity\FormulaireDemandeProduit;
use App\Form\FormulaireDemandeProduitType;
use App\Form\ReponseFormulaireType;
use App\Repository\ClientsRepository;
use App\Repository\FormulaireDemandeProduitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formulaire/demande/produit')]
class FormulaireDemandeProduitController extends AbstractController
{
    #[Route('/', name: 'app_formulaire_demande_produit_index', methods: ['GET'])]
    public function index( FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository, ClientsRepository $clientsRepository): Response
    {
        return $this->render('formulaire_demande_produit/index.html.twig', [
            'formulaire_demande_produits' => $formulaireDemandeProduitRepository->findAll(),
        ]);
    }



    #[Route('/{id}/creer', name: 'app_formulaire_demande_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository, int $id ): Response   
    {
        $formulaireDemandeProduit = new FormulaireDemandeProduit();
        $form = $this->createForm(FormulaireDemandeProduitType::class, $formulaireDemandeProduit);
        $form->handleRequest($request);

        // $client = $clientsRepository ->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $newFormulaire = new FormulaireDemandeProduit();

            // $newFormulaire->setRefClient($client);
            $newFormulaire->setTypeProduit($form->get('typeProduit')->getData());
            $newFormulaire->setDescriptionProduit($form->get('descriptionProduit')->getData());

            // Permet d enregistrer la date et l heure de l envoie (format gmt a changer)
            $dateEnvoiForm = new \DateTime();
            $newFormulaire->setDateEnvoieForm($dateEnvoiForm);

            // Définie la reponse du form en 'attente'
            $attenteReponse = 'attente';
            $newFormulaire->setReponseDemande($attenteReponse);

            // dd($newFormulaire);
            $entityManager->persist($newFormulaire);
            $entityManager->flush();

            // renvoie à la page liste des formulaire
            return $this->redirectToRoute('app_formulaire_demande_produit_show_liste', ['user_id'=> $id], Response::HTTP_SEE_OTHER);  
        }

        return $this->render('components/user_view/new_form.html.twig', [
    
            'formulaire_demande_produit' => $formulaireDemandeProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formulaire_demande_produit_show', methods: ['GET'])]
    public function show(int $id, FormulaireDemandeProduit $formulaireDemandeProduit, FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository): Response
    {
        $desc = $formulaireDemandeProduitRepository->find($id);

        $description = 
        $desc->setDescriptionProduit(wordwrap($desc->getDescriptionProduit(),20, 10));

        return $this->render('formulaire_demande_produit/show.html.twig', [
            'formulaire_demande_produit' => $formulaireDemandeProduit,
        ]);
    }



    #[Route('/{id}/liste', name: 'app_formulaire_demande_produit_show_liste', methods: ['GET'])]
    public function showListe( int $id ,ClientsRepository $clientsRepository,
    UserRepository $userRepository,
                              FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository ): Response
    {
        // $listeFormulaires = $formulaireDemandeProduitRepository->findAllFormsByClient($user_id);
        $listeFormulaires = $formulaireDemandeProduitRepository->findAllForms($id);
        // $client = $clientsRepository->find($user_id);
        $user = $userRepository->find($id);
        
        // dd($client);
        return $this->render('components/user_view/form_list.html.twig', [
            'formulaire_demande_produits' => $listeFormulaires,
            'user' => $user 
            // 'client' => $client,
            // dd($client),
        ]);
    }

    

    // Permet de donner une réponse à la demande
    #[Route('/traiter/formulaire/{id}', name: 'app_formulaire_demande_produit_traiter', methods: ['GET', 'POST'])]
    public function traiter(int $id, Request $request, FormulaireDemandeProduit $formulaireDemandeProduit,
     FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository, ReponseFormulaireType $reponseFormulaireType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseFormulaireType::class, $formulaireDemandeProduit);
        $form->handleRequest($request);

        $formRecup = $formulaireDemandeProduitRepository->find($id);


        // dd($form->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez la réponse du vendeur dans l'entité
            $dateReponseForm = new \DateTime();
            $formRecup->setDateReponseForm($dateReponseForm);

            $entityManager->persist($formulaireDemandeProduit);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page de confirmation ou autre
            return $this->redirectToRoute('app_formulaire_demande_produit_index');
        }

        return $this->render('formulaire_demande_produit/traiter.html.twig', [
            'formulaire_demande_produit' => $formulaireDemandeProduit,
            'form' => $form,
        ]);
    }

    // //Crud pour modifier le formulaire
    // #[Route('/{id}/edit', name: 'app_formulaire_demande_produit_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, FormulaireDemandeProduit $formulaireDemandeProduit, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(FormulaireDemandeProduitType::class, $formulaireDemandeProduit);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_formulaire_demande_produit_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('formulaire_demande_produit/edit.html.twig', [
    //         'formulaire_demande_produit' => $formulaireDemandeProduit,
    //         'form' => $form,
    //     ]);
    // }

    // // Crud pour supprimer le formulaire --> VOIR SI PAS POSSIBLE DE LE "CACHER"
    // #[Route('/{id}', name: 'app_formulaire_demande_produit_delete', methods: ['POST'])]
    // public function delete(Request $request, FormulaireDemandeProduit $formulaireDemandeProduit, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$formulaireDemandeProduit->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($formulaireDemandeProduit);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_formulaire_demande_produit_index', [], Response::HTTP_SEE_OTHER);
    // }
}
