<?php

namespace App\Controller;

use App\Entity\FormulaireDemandeProduit;
use App\Form\FormulaireDemandeProduitType;
use App\Form\ReponseFormulaireType;
use App\Repository\FormulaireDemandeProduitRepository;
use App\Repository\UserRepository;
use App\Service\UtilsServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formulaire/demande/produit')]
class FormulaireDemandeProduitController extends AbstractController
{

    // Route pour ROLE_ADMIN

    #[Route('/', name: 'app_formulaire_demande_produit_index', methods: ['GET'])]
    public function index( FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository ): Response
    {
        return $this->render('/pages/Administration/formulaires/list_forms_product.html.twig', [
            'formulaires' => $formulaireDemandeProduitRepository->findAll(),
        ]);
    }



    #[Route('/{id}/creer', name: 'app_formulaire_demande_produit_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, UserRepository $userRepository, 
        FormulaireDemandeProduitRepository $formulaireRepository,  EntityManagerInterface $entityManager ): Response   
    {
        $user = $userRepository->find(UtilsServices::cleanInput ( $id ));

        $formulaireDemandeProduit = $formulaireRepository->find($id);
        $form = $this->createForm(FormulaireDemandeProduitType::class, $formulaireDemandeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Crée un nouvel enregistrement pour l'entité Formulaire 
            $newForm = new FormulaireDemandeProduit();

            // Ajoute l'utilisateur actuel dans l'enregistrement
            $newForm->setUser($user);

            // Ajoute les données saisies
            $newForm->setTypeProduit( UtilsServices::cleanInput ( $form->get('typeProduit')->getData() ) );
            $newForm->setDescriptionProduit(UtilsServices::cleanInput ( $form->get('descriptionProduit')->getData() ) );

            // Permet d enregistrer la date et l heure de l envoie (format gmt a changer)
            $dateEnvoiForm = new \DateTime();
            $newForm->setDateEnvoieForm($dateEnvoiForm);

            // Définie la reponse du form en 'attente'
            $attenteReponse = 'attente';
            $newForm->setReponseDemande(UtilsServices::cleanInput( $attenteReponse) );

            $entityManager->persist($newForm); // persiste les données
            $entityManager->flush(); // Met à jour la base de donnés

            // renvoie à la page liste des formulaire
            return $this->redirectToRoute('app_formulaire_demande_produit_show_liste', ['id'=> $id], Response::HTTP_SEE_OTHER);  
        }

        return $this->render('pages/User/formulaires/new_form.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_formulaire_demande_produit_show', methods: ['GET'])]
    public function show(int $id, FormulaireDemandeProduit $formulaireDemandeProduit, FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository): Response
    {
        $desc = $formulaireDemandeProduitRepository->find($id);

        $description = 
        $desc->setDescriptionProduit(wordwrap($desc->getDescriptionProduit(),20, 10));

        return $this->render('pages/User/formulaires/show_one_form.html.twig', [ // a modifier
            'formulaire_demande_produit' => $formulaireDemandeProduit,
        ]);
    }



    #[Route('/{id}/liste', name: 'app_formulaire_demande_produit_show_liste', methods: ['GET'])]
    public function showUserListe( int $id, UserRepository $userRepository, FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository ): Response
    {
        return $this->render('pages/User/formulaires/form_list.html.twig', [
            'formulaires' => $listeFormulaires= $formulaireDemandeProduitRepository->findAllForms($id),
            'user' => $user = $userRepository->find($id) 

        ]);
    }

    

    // Permet de donner une réponse à la demande
    #[Route('/traiter/formulaire/{id}', name: 'app_formulaire_demande_produit_traiter', methods: ['GET', 'POST'])]
    public function traiter(int $id, Request $request, FormulaireDemandeProduit $formulaireDemandeProduit,
     FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository, EntityManagerInterface $entityManager): Response
    {
        // création du formulaire celon le modèle donné --> ReponseFormmulaireType
        $form = $this->createForm(ReponseFormulaireType::class, $formulaireDemandeProduit);
        $form->handleRequest($request);

        // Récupère le formulaire sélectionner dans le repository grâce a son id
        $formRecup = $formulaireDemandeProduitRepository->find($id);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrez la réponse du vendeur dans l'entité
            $dateReponseForm = new \DateTime(); // récupère la date au moment de la soumission du formulaire
            $formRecup->setDateReponseForm($dateReponseForm);

            $entityManager->persist($formulaireDemandeProduit); // persist les données recupéré
            $entityManager->flush(); // Met a jour l'enregistrement en base de données

            // Redirige l'administrateur vers la page qui contient la liste de tous les formulaires
            return $this->redirectToRoute('app_formulaire_demande_produit_index');
        }

        // renvoie sur la page de traitement du formulaire
        return $this->render('pages/Administration/formulaires/gestion/traiter_form.html.twig', [
            'formulaire_demande_produit' => $formulaireDemandeProduit,
            'form' => $form,
        ]);
    }


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
