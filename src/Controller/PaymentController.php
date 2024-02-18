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
                    "unit_amount_decimal" => $produit->getPrixProduit(),
                    // 'unit_amount' => $produit->getPrixProduit(),

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
                'unit_amount_decimal' => $transportPrix,
                'product_data' => [
                    'name' => $transporteur,
                ]
            ],
            'quantity' => $commande->getQuantite(),
        ];

        // dd($produitStripe);


        // foreach()

        $stripeSecretKey = "sk_test_51OkvVjCM4LcP8MarBx2mlHjNk7ZogncczHVyB3EmQLEr8gRntUmQcMAvofYEs9h8YEFblVVtVJoPnJGSCCoHAbre00dYPqgb6s";
        $stripe = new \Stripe\StripeClient($stripeSecretKey);
        Stripe::setApiKey($stripeSecretKey);


        // dd($produitStripe);

        $checkout_session = Session::create([

            'customer_email' => $commande->getUser()->getEmail(),
            'payment_method_types' => ['card'],


            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                // 'price' => '{{PRICE_ID}}',
                // 'quantity' => 1,
                $produitStripe,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generator->generate(name: 'app_payment_success', parameters: ['cmd_id' => $commande->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generator->generate(name: 'app_payment_error', parameters: ['user_id' => $commande->getUser()->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // cmd ajt attribut stripe

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/order/success/{cmd_id}', name: 'app_payment_success')]
    public function stripeSucces($cmd_id,): HttpFoundationResponse
    {
        // voir pour mettre  cmd -> crée function pour récup la dernière
        // return $this->render('app_commandes_show_one', ['id' => $cmd_id] );

        // retourne toute les cmds
        // return $this->render('app_commandes_user_show', ['id_panier' => $id_panier->getUser()->getPanier()->getId()]);

        return $this->render('app_commandes_show_one', ['cmd_id' => $cmd_id]);
    }

    #[Route('/order/cancel/{user_id}', name: 'app_payment_error')]
    public function stripeCancel($user_id): HttpFoundationResponse
    {
        return $this->render('app_panier_show', ['user_id' => $user_id]);
    }


    // #[Route('/order/create-session-stripe/{id_panier}', name: 'app_payment')]
    // public function processPayment(int $id_panier)
    // {
    //     // Configuration de la clé secrète Stripe
    //     Stripe::setApiKey($this->getParameter('stripe_secret_key'));

    //     $commande = $this->em->getRepository(Panier::class)->findOneBy(['id' => $id_panier]);

    //     // Création d'un paiement avec Stripe
    //     $charge = Charge::create([
    //         'amount' => 1000, // Montant en centimes (10$)
    //         'currency' => 'usd',
    //         'source' => 'tok_visa', // Token de carte de crédit factice pour les tests
    //         'description' => 'Example Payment'
    //     ]);

    //     // Traitement de la réponse de Stripe
    //     if ($charge->status == 'succeeded') {
    //         // Le paiement a réussi
    //         return $this->redirectToRoute('app_payment_success', ['id_panier' => $commande->getId() ]);
    //     } else {
    //         // Le paiement a échoué
    //         return $this->redirectToRoute('app_payment_error');
    //     }
    // }
}
