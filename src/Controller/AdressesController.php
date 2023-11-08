<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressesType;
use App\Repository\AdressesRepository;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresses')]
class AdressesController extends AbstractController
{
    #[Route('/', name: 'app_adresses_index', methods: ['GET'])]
    public function index(AdressesRepository $adressesRepository, ClientsRepository $clientsRepository): Response
    {
        $client = $clientsRepository->findAll();
        return $this->render('adresses/index.html.twig', [
            'adresses' => $adressesRepository->findAll(),
            'client' => $client,
        ]);
    }



    // Route pour ajouter les adresse en bdd (voir pour bloquer à max 3 ou -)
    #[Route('/{user_id}/{id_client}/new', name: 'app_adresses_new', methods: ['GET', 'POST'])]
    public function new(int $id_client, int $user_id, Request $request, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository): Response
    {
        $adress = new Adresses();
        
        $form = $this->createForm(AdressesType::class, $adress);
        
        // $form->add('validation_groups', ChoiceType::class, [
        //     'choices' => [
        //         'client' => 'client',
        //     ],
        // ]);
        $form->handleRequest($request);
        $client = $clientsRepository->find($id_client);

        // dd($client);

        if ($form->isSubmitted() && $form->isValid()) {

            $adress->setIdClientAdresses($client);
            $client->addAdress($adress);

            $entityManager->persist($client);
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_show', ['user_id' => $user_id ,'id_client' => $id_client] , Response::HTTP_SEE_OTHER); 
             
        }

        return $this->render('adresses/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_adresses_show', methods: ['GET'])]
    public function show(Adresses $adress): Response
    {
        return $this->render('adresses/show.html.twig', [
            'adress' => $adress,
        ]);
    }


    

    #[Route('/{id}/edit', name: 'app_adresses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresses $adress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdressesType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adresses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adresses/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresses_delete', methods: ['POST'])]
    public function delete(Request $request, Adresses $adress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adresses_index', [], Response::HTTP_SEE_OTHER);
    }
}
