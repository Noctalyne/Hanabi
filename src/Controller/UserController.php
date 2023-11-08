<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\User;
use App\Form\ClientsType;
use App\Form\UserType;
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
        return $this->render('user/index.html.twig', [
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
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
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
        ClientsRepository $clientsRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        $user = $userRepository->find($id);
        $actuel = $userRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $client = $clientsRepository->findClient($id);

            $user = $form->getData(); // recup les info du form

            $entityManager->persist($user); // persist client en bdd

            $entityManager->flush();

            // dd($client);

            # si il y a un client la modif renvoie à la page profil de client
            if ($client != null) {
                return $this->redirectToRoute('app_clients_show', ['user_id' => $id], Response::HTTP_SEE_OTHER);
            }

            # sinon après la modif renvoie à la page profil de client
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'actuel' => $actuel,
        ]);
    }


    // Permet de modifier la partie user de client
    #[Route('/{user_id}/edit', name: 'app_user_edit_of_client', methods: ['GET', 'POST'])]
    public function editUserOfClient(int $user_id, Request $request, User $user,  ClientsRepository $clientsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $client = $clientsRepository->find($user_id);

        if ($form->isSubmitted() && $form->isValid()) {

            $client = $form->getData(); // recup les info du form

            $entityManager->persist($client); // persist client en bdd

            $entityManager->flush();

            // dd($client);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }









    // permet de crée un client à partir d'un user 
    #[Route('/{user_id}/create', name: 'app_user_create_client', methods: ['GET', 'POST'])]
    public function createClient(int $user_id, Request $request, UserRepository $userRepository, Clients $client, EntityManagerInterface $entityManager): Response
    {
        $formClient = $this->createForm(ClientsType::class, $client);
        $formClient->handleRequest($request);

        $user = $userRepository->find($user_id);

        if ($formClient->isSubmitted() && $formClient->isValid()) {

            $newClient = new Clients();

            $newClient->setUser($user);
            $newClient->setNom($formClient->get('nom')->getData());
            $newClient->setPrenom($formClient->get('prenom')->getData());
            $newClient->setTelephone($formClient->get('telephone')->getData());

            $entityManager->persist($newClient);

            // dd($client);
            $entityManager->flush();

            // return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_clients_show', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }

        // Renvoie sur la page qui crée un nouveau client 
        return $this->render('clients/new.html.twig', [
            'user' => $user,
            'client' => $client,
            'form' => $formClient,
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
