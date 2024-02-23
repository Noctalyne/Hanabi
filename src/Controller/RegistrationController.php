<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\MessagerieServices;
use App\Service\UtilsServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private UserRepository $userRepository;
    private MessagerieServices $messagerieService ;

    public function __construct(UserRepository $userRepository, MessagerieServices $messagerieService) {
        $this->userRepository= $userRepository;
        $this->messagerieService = $messagerieService;
    }

    #[Route('/register', name: 'app_register')]
    public function register(UserPasswordHasherInterface $hash, EntityManagerInterface $entityManager,Request $request): Response 
    {
        $msg = "";
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($this->userRepository-> findOneBy (['email'=> $form->get("email")->getData() ] )) {
                $msg = "L'adresse noctalyne@laposte.net est déjà utiliser."; 
            }

            if ($form->isValid()) {

                // les données récupérés
                $pass = UtilsServices::cleanInput($form->get('password')->getData() );
                $hash = $hash->hashPassword($user, $pass); // hash le mot de passe
                // Récupère puis clean les données nettoyé dans l utilisateur
                $user->setEmail(UtilsServices::cleanInput($form->get('email')->getData()));
                $user->setPassword($hash);
                $user->setRoles(['ROLE_USER']);
                // Bloque les accès tant que utilisateur pas identifier
                $user->setAccountActivate(false);
                $user->setClientActivate(false);

                $entityManager->persist($user);
                $entityManager->flush(); // Enregistre les données nettoyé dans l utilisateur

                

                $destinataire = $user->getEmail();
                $object = "Activer votre compte";
                $content = "Pour activer le compte cliquer sur le lien ci-dessous :
                    <a href='https://localhost:8000/register/activate/".$user->getId()."'>Activer</a>";

                $this->messagerieService->sendMail($object, $content, $destinataire);

                $msg =  "Veuillez vérifier la boite de reception de l'email fournis. ";

            }
            // return $this->redirectToRoute('app_login', ['msg' => $msg], Response::HTTP_SEE_OTHER);

            return $this->redirectToRoute('app_login', ['msg' => $msg], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form ->createView(),
            'msg' => $msg,
        ]);
    }

    #[Route('/register/activate/{id}', name: 'app_register_activate')]
    public function activateAccount(int $id, EntityManagerInterface $entityManager){

        $id = UtilsServices::cleanInput($id);
        $user = $this->userRepository->find($id);
       
        if ($user){
            $user->setAccountActivate(true);
            $entityManager->flush();
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        else {
            return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/register/sendMail/{id}', name: 'app_register_send_mail')]
    public function sendMailActivation(int $id){

        $id = UtilsServices::cleanInput($id);
        $user = $this->userRepository->find($id);

        if ($user->isAccountActivate() != true ){ 
            
            $object = "Votre lien d'activation";
            $content = "Pour activer le compte cliquer sur le lien ci-dessous :
                <a href='https://localhost:8000/register/activate/".$user->getId()."'>Activer</a>";

            $this->messagerieService->sendMail($object, $content, $user->getEmail());
            
            // $msg = "Vérifier votre adresse email";, ['msg' => $msg,]

            return $this->redirectToRoute('app_logout'); // deco pour évité que l user reste connecter
        }
        
        
        return $this->redirectToRoute('app_register');
    }
}
