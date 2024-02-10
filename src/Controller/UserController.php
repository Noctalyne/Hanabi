<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Entity\Clients;
use App\Entity\User;
use App\Form\AdressesType;
use App\Form\ClientsType;
use App\Form\UserType;
use App\Repository\AdressesRepository;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/list_user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, User $user, AdressesRepository $adressesRepository): Response
    {

        $adresses = $adressesRepository->findAdresses($id);


        // $listeAdresses = $adressesRepository->findAdresses($id);

        $limiteAdress = count($adresses);

        return $this->render('user/show_user.html.twig', [
            'adresses' => $adresses,
            'limiteAdress' => $limiteAdress,
            'user' => $user,
        ]);
    }


    //   Modifie user si pas d'info client
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        User $user,
        UserRepository $userRepository,
        // ClientsRepository $clientsRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        $user = $userRepository->find($id);
        $actuel = $userRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            // $client = $clientsRepository->findClient($id);

            $user = $form->getData(); // recup les info du form

            $entityManager->persist($user); // persist client en bdd

            $entityManager->flush();


            # si il y a un client la modif renvoie à la page profil de client
            // if ($client != null) {
            //     return $this->redirectToRoute('app_clients_show', ['user_id' => $id], Response::HTTP_SEE_OTHER);
            // }

            # sinon après la modif renvoie à la page profil de client
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_user.html.twig', [
            'user' => $user,
            'form' => $form,
            'actuel' => $actuel,
        ]);
    }


    // Permet de modifier la partie user de client
    // #[Route('/{user_id}/edit', name: 'app_user_edit_of_client', methods: ['GET', 'POST'])]
    // public function editUserOfClient(int $user_id, Request $request, User $user,  ClientsRepository $clientsRepository, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     $client = $clientsRepository->find($user_id);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $client = $form->getData(); // recup les info du form

    //         $entityManager->persist($client); // persist client en bdd

    //         $entityManager->flush();

    //         // dd($client);
    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/edit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }









    // permet de crée un client à partir d'un user 
    // #[Route('/{user_id}/create', name: 'app_user_create_client', methods: ['GET', 'POST'])]
    // public function createClient(int $user_id, Request $request, UserRepository $userRepository, Clients $client, EntityManagerInterface $entityManager): Response
    // {
    //     $formClient = $this->createForm(ClientsType::class, $client);
    //     $formClient->handleRequest($request);

    //     $user = $userRepository->find($user_id);

    //     if ($formClient->isSubmitted() && $formClient->isValid()) {

    //         $newClient = new Clients();

    //         $newClient->setUser($user);
    //         $newClient->setNom($formClient->get('nom')->getData());
    //         $newClient->setPrenom($formClient->get('prenom')->getData());
    //         $newClient->setTelephone($formClient->get('telephone')->getData());

    //         $entityManager->persist($newClient);

    //         // dd($client);
    //         $entityManager->flush();

    //         // return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    //         return $this->redirectToRoute('app_clients_show', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
    //     }

    //     // Renvoie sur la page qui crée un nouveau client 
    //     return $this->render('clients/new.html.twig', [
    //         'user' => $user,
    //         'client' => $client,
    //         'form' => $formClient,
    //     ]);
    // }




    // permet de crée un client à partir d'un user 
    #[Route('/{id}/add/infos', name: 'app_user_add_infos', methods: ['GET', 'POST'])]
    public function addInfos(int $id, Request $request, UserRepository $userRepository, AdressesRepository $adressesRepository, EntityManagerInterface $entityManager): Response
    {

        // $actuel = $userRepository->find($id);
        $user = $userRepository->find($id);

        $formClient = $this->createForm(ClientsType::class, $user);
        $formClient->handleRequest($request);


        // $adresse = new Adresses();
        // $formAdresse = $this->createForm(AdressesType::class, $adresse);
        

        if ($formClient->isSubmitted() && $formClient->isValid()) {

        //  && $formAdresse->isSubmitted() && $formAdresse->isValid() 

            $user->setNom($formClient->get('nom')->getData());
            $user->setPrenom($formClient->get('prenom')->getData());
            $user->setTelephone($formClient->get('telephone')->getData());


            // $user->addListAdress($formAdresse->getData());



            $user->setClientActivate(true);

            $entityManager->persist($user);

            // dd($client);
            $entityManager->flush();

            // return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        // Renvoie sur la page qui crée un nouveau client 
        // return $this->render('user/new.html.twig', [
            return $this->render('user/add_infos.html.twig', [

                'user' => $user,
                'form' => $formClient,
                // 'formAdresse' =>$formAdresse,
            // 'actuel' => $actuel,
        ]);
    }


    // Voir pour que cela désactive juste le compte --> ajouter booleen a l entity 
    //  Voir pour aussi faire en sorte qu'un puisse réactiver le compte 



    // #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    // public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($user);
    //         $entityManager->flush();
    //     }

    //     // return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    // }
}
