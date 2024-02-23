<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MessagerieServices
{
    private ?string $login;
    private ?string $password;
    private ?string $serveur;
    private ?int $port;
    public function __construct(string $login, string $password, string
    $serveur, int $port)
    {
        $this->login = $login;
        $this->password = $password;
        $this->serveur = $serveur;
        $this->port = $port;
    }
    public function sendMail($objet, $content, $destinataire)
    {
        //Load Composer's autoloader
        require '../vendor/autoload.php';
        
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Paramètres serveur 
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $this->serveur; 
            $mail->SMTPAuth = true;
            $mail->Username = $this->login;  
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $this->port;

            //expéditeur
            $mail->setFrom($this->login, '');
            //destinataire
            $mail->addAddress($destinataire);
            
            //Contenu
            $mail->isHTML(true);
            $mail->Subject = $objet;
            $mail->Body = $content;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            //envoi du mail
            $mail->send();

            return 'Le mail à été envoyé avec succès';

        } catch (\Exception $e) {
            return "Erreur Mail :{$mail->ErrorInfo}";
        }
    }
}
