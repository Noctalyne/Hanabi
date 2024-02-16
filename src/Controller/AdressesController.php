<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressesType;
use App\Repository\AdressesRepository;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface; // pour valider le max 3 adrss

#[Route('/adresses')]
class AdressesController extends AbstractController
{
    // #[Route('/', name: 'app_adresses_index', methods: ['GET'])]
    // public function index(AdressesRepository $adressesRepository, ClientsRepository $clientsRepository): Response
    // {
    //     $client = $clientsRepository->findAll();
    //     return $this->render('adresses/index.html.twig', [
    //         'adresses' => $adressesRepository->findAll(),
    //         'client' => $client,
    //     ]);
    // }

    // #[Route('/{user_id}/{id_client}/liste', name: 'app_adresses_show_liste', methods: ['GET'])]
    // public function showListe(int $id_client, int $user_id, ClientsRepository $clientsRepository, AdressesRepository $adressesRepository): Response
    // {
    //     $adresses = $adressesRepository->findAdresses($user_id);
    //     $client = $clientsRepository->find($id_client);

    //     return $this->render('adresses/afficherListeAdresse.html.twig', [
    //         'adresses' => $adresses,
    //         'client' => $client,
    //     ]);
    // }


    

    // Route pour ajouter les adresse en bdd (voir pour bloquer à max 3 ou -)
    #[Route('/{id}/new', name: 'app_adresses_new', methods: ['GET', 'POST'])]
    public function new (
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        AdressesRepository $adressesRepository,
    ): Response {

        $msg = '';
        $adress = new Adresses();

        $form = $this->createForm(AdressesType::class, $adress);

        $listeAdresses = $adressesRepository->findAdresses($id);

        $form->handleRequest($request);
        $user = $userRepository->find($id);

        $limiteAdress = count($listeAdresses);


        if ($limiteAdress >= 3 ) { // si le client à déja 3 adresses

            $msg = 'Vous ne pouvez pas avoir plus de 3 adresses enregistrer. Veuillez en supprimez une ou  modifier celles existante.';
            return $this->render('adresses/new.html.twig', [
                'adress' => $adress,
                'form' => $form,
                'msg' => $msg,
                'user' => $user,
                'limite' => $limiteAdress,
            ]);
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $msg = 'Bravo votre adresse à été mise à jour.';

            $user->addListAdress($adress);

            $entityManager->persist($user);
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $id ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/User/adresses/_new_adresse.html.twig', [
            'adress' => $adress,
            'form' => $form,
            'msg' => $msg,
            'user' => $user,
            'limite' => $limiteAdress,
        ]);
    }




    #[Route('/{id}/{id_adrss}/show', name: 'app_adresses_show', methods: ['GET'])]
    public function show(int $id_adrss, AdressesRepository $adressesRepository): Response
    {
        return $this->render('pages/User/adresses/modal_show_one_adresse.html.twig', [
            'adresse' => $adressesRepository->findOneBy(['id' => $id_adrss]),
        ]);
    }




    #[Route('/{id}/{id_adrss}/edit', name: 'app_adresses_edit', methods: ['GET', 'POST'])]
    public function edit(int $id_adrss, int $id, Request $request, AdressesRepository $adressesRepository, EntityManagerInterface $entityManager): Response
    {
        $adresse = $adressesRepository->findOneBy(['id' => $id_adrss]);

        $form = $this->createForm(AdressesType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_show', ['id' => $id, 'id_adrss' => $id_adrss], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/User/adresses/_edit_adresse.html.twig', [
            'adresse' => $adresse ,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresses_delete', methods: ['POST'])]
    public function delete(Request $request, Adresses $adress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adresses_index', [], Response::HTTP_SEE_OTHER);
    }
}
