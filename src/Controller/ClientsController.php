<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\AdressesRepository;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clients')]
class ClientsController extends AbstractController
{
    #[Route('/', name: 'app_clients_index', methods: ['GET'])]
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAllWithUser(),
        ]);
    }


    #[Route('/{user_id}', name: 'app_clients_show', methods: ['GET'])]
    public function show(int $user_id, Clients $client, ClientsRepository $clientsRepository, UserRepository $userRepository, AdressesRepository $adressesRepository): Response
    {
        $user = $userRepository->find($user_id);
        $client = $clientsRepository->findClient($user_id);
        $adresses = $client->getAdresses();
        // $adresses = $adressesRepository->findBy(array ('idClientAdresse' => $user_id) );
        // dd($client);
        // dd($adresses);

        return $this->render('clients/show.html.twig', [
            'adresses' => $adresses,
            'client' => $client,
            'user' => $user,
        ]);
    }

    

    // si user possède déja un client
    #[Route('/{user_id}/edit', name: 'app_clients_edit', methods: ['GET', 'POST'])]
    public function editClient(int $user_id, Request $request, Clients $client, UserRepository $userRepository, 
            ClientsRepository $clientsRepository, EntityManagerInterface $entityManager): Response
    {
        $formClient = $this->createForm(ClientsType::class, $client);
        $formClient->handleRequest($request);

        $user = $userRepository->find($user_id);

        $actuel = $clientsRepository->findClient($user->getId());

        if ($formClient->isSubmitted() && $formClient->isValid()) {

            $client->setUser($user);

            // force la maj des informations sans forcément modifier les autres -> évite la contrainte de foreign key
            $clientsRepository->forceUpdate($client, $user_id); 

            $entityManager->flush(); // Envoie en BDD

            return $this->redirectToRoute('app_clients_show', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'user' => $user,
            'client' => $client,
            'form' => $formClient,
            'actuel' => $actuel,
        ]);
    }

    // si le user n est pas encore inscrit comme client
    #[Route('/{user_id}/edit', name: 'app_clients_empty_edit', methods: ['GET', 'POST'])]
    public function editEmptyClient(int $user_id, Request $request, Clients $client, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
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
            // $clientsRepository->forceUpdate($client, $user_id); // force la maj des informations sans forcément modifier les autres -> évite la contrainte de foreign key

            $entityManager->persist($newClient);

            // dd($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'user' => $user,
            'client' => $client,
            'form' => $formClient,
        ]);
    }



    #[Route('/{id}', name: 'app_clients_delete', methods: ['POST'])]
    public function delete(Request $request, Clients $client, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
