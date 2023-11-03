<?php

namespace App\Controller;

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
            // 'last_username' => $lastUsername, 
            // 'error' => $error
        ]);
    }



    // //Route accessibile si connecter 
    // #[Route('/contact/Formulaire', name: 'app_contact_log')]
    // public function contactAcces(FormulaireDemandeProduitRepository $formulaireDemandeProduitRepository): Response
    // {

    //     return $this->render('contact/contact_log.html.twig', [
    //         'formulaire_demande_produits' => $formulaireDemandeProduitRepository->findAll(),
    //     ]);
    // }







    // // Route qui renvoie au formulaire de demande
    // #[Route('/contact/Formulaire/Creer/{user_id}', name: 'app_contact_log_cree_form')]
    // public function creerDemande(Request $request, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository, UserRepository $userRepository, int $user_id): Response
    // {
    //     $formulaireDemandeProduit = new FormulaireDemandeProduit();
    //     $form = $this->createForm(FormulaireDemandeProduitType::class, $formulaireDemandeProduit);
    //     $form->handleRequest($request);

    //     $client = $clientsRepository->findClient($user_id);
        
    //     $user = $userRepository->find($user_id);
    //     echo ('<pre>'),var_dump($user);echo ('</pre>');
    //     // dd($user);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $client->setUser($user);

    //         $formulaireDemandeProduit->setRefClient($client);
    //         // dd($formulaireDemandeProduit);
            
    //         // Permet d enregistrer la date et l heure de l envoie (format gmt a changer)
    //         $dateEnvoiForm = new \DateTime();
    //         $formulaireDemandeProduit->setDateEnvoieForm($dateEnvoiForm);

    //         // Définie la reponse du form en 'attente'
    //         $attenteReponse = 'attente';
    //         $formulaireDemandeProduit->setReponseDemande($attenteReponse);
            
    //         // dd($formulaireDemandeProduit);

    //         $entityManager->persist($formulaireDemandeProduit);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_contact_log', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('./formulaire_demande_produit/new.html.twig', [
    //         'formulaireDemande' => $formulaireDemandeProduit,
    //         'form' => $form,
    //     ]);;
    // }


    // // Afficher le formulaire celon son id 
    // #[Route('/contact/Formulaire/Voir:{id}', name: 'app_formulaire_show', methods: ['GET'])]
    // public function show(FormulaireDemandeProduit $formulaireDemandeProduit): Response
    // {
    //     return $this->render('contact/formulaireShow.html.twig', [
    //         'formulaireDemande' => $formulaireDemandeProduit,
    //     ]);
    // }



}
