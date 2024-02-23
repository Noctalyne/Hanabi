<?php

namespace App\Controller;

use Amp\Http\Client\Response;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Stripe\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{


    // private UserRepository $userRepository;
    // private ProduitsRepository $produitsRepository;
    private PanierRepository $panierRepository;
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generator;

    // public function __construct(UserRepository $userRepository, ProduitsRepository $produitsRepository) {
    public function __construct(PanierRepository $panierRepository, EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->panierRepository = $panierRepository;
        $this->generator = $generator;
        // $this->userRepository= $userRepository;
        // $this->produitsRepository= $produitsRepository;
    }

    #[Route('/order/create-session-stripe/{id_panier}', name: 'app_payment')]
    public function stripeCheckOut($id_panier): RedirectResponse
    {
        // Trouve la panier correspondant via le repository
        $commande = $this->em->getRepository(Panier::class)->findOneBy(['id' => $id_panier]);


        $listProduit = $commande->getListeProduits();

        // Test d'un transporteur
        $transporteur = "Mondial Relay";
        $transportPrix =  "10.00";

        $produitStripe = [];


        if (!$commande) {
            return $this->redirectToRoute(route: 'app_panier_show');
        }


        foreach ($listProduit as $produit) {



            $produitStripe[] = [

                'price_data' => [
                    'currency' => 'eur',
                    // "custom_unit_amount" => 'decimal',
                    // "unit_amount"=> 1000,
                    // "unit_amount_decimal" => $produit->getPrixProduit(),
                    'unit_amount' => $produit->getPrixProduit() * 100,

                    'product_data' => [
                        'name' => $produit->getNomProduit(),
                    ]
                ],
                'quantity' => $commande->getQuantite(),
            ];

        };

        // transport
        $produitStripe[] = [

            'price_data' => [
                'currency' => 'eur',
                'unit_amount_decimal' => $transportPrix * 100,
                'product_data' => [
                    'name' => $transporteur,
                ]
            ],
            'quantity' => $commande->getQuantite(),
        ];

        // dd($produitStripe);


        $stripeSecretKey = "sk_test_51OkvVjCM4LcP8MarBx2mlHjNk7ZogncczHVyB3EmQLEr8gRntUmQcMAvofYEs9h8YEFblVVtVJoPnJGSCCoHAbre00dYPqgb6s";
        $stripe = new \Stripe\StripeClient($stripeSecretKey);
        Stripe::setApiKey($stripeSecretKey);



        $checkout_session = Session::create([

            'customer_email' => $commande->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $produitStripe,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generator->generate(name: 'app_payment_success', parameters: ['cmd_id' => $commande->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generator->generate(name: 'app_payment_error', parameters: ['user_id' => $commande->getUser()->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // if (checkout_session)

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/order/success/{cmd_id}', name: 'app_payment_success')]
    public function stripeSucces($cmd_id,): HttpFoundationResponse
    {
        // voir pour mettre  cmd -> crée function pour récup la dernière

        return $this->render('app_commandes_show_one', ['cmd_id' => $cmd_id]);
    }

    #[Route('/order/cancel/{user_id}', name: 'app_payment_error')]
    public function stripeCancel($user_id): HttpFoundationResponse
    {
        return $this->render('app_panier_show', ['user_id' => $user_id]);
    }


    
}
