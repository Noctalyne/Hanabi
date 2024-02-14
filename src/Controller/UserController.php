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
use App\Repository\FormulaireDemandeProduitRepository;
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
        return $this->render('pages/Administration/user/list_user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, User $user, AdressesRepository $adressesRepository, FormulaireDemandeProduitRepository $formDemande): Response
    {
        $adresses = $adressesRepository->findAdresses($id);
        $formulaires = $formDemande->findAllForms($id);

        $limiteAdress = count($adresses);

        return $this->render('/pages/User/show_user.html.twig', [
            'user' => $user,            
            'adresses' => $adresses,
            'limiteAdress' => $limiteAdress,
            'formulaires' => $formulaires,
        ]);
    }

    //   Modifie user si pas d'info client
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        User $user,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        $user = $userRepository->find($id);
        $actuel = $userRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); // recup les info du form
            $entityManager->persist($user); // persist client en bdd
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_user.html.twig', [
            'user' => $user,
            'form' => $form,
            'actuel' => $actuel,
        ]);
    }


    #[Route('/{id}/add/infos', name: 'app_user_add_infos', methods: ['GET', 'POST'])]
    public function addInfos(int $id, Request $request, UserRepository $userRepository, AdressesRepository $adressesRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $userRepository->find($id);

        $formClient = $this->createForm(ClientsType::class, $user);
        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {

            $user->setNom($formClient->get('nom')->getData());
            $user->setPrenom($formClient->get('prenom')->getData());
            $user->setTelephone($formClient->get('telephone')->getData());

            $user->setClientActivate(true);

            $entityManager->persist($user);

            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        // Renvoie sur la page qui crée un nouveau client 
            return $this->render('user/add_infos.html.twig', [
                'user' => $user,
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
