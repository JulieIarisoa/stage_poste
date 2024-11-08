<?php
// src/Service/EmailService.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $body): bool
    {
        // Création de l'email
        $email = (new Email())
            ->from('julieharisoa9@gmail.com')  // L'email de l'expéditeur
            ->to($to)                        // L'email du destinataire
            ->subject($subject)              // Sujet de l'email
            ->text($body);                   // Corps du message en texte brut

        try {
            // Envoi de l'email
            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            // Gérer les erreurs d'envoi d'email
            return false;
        }
    }
}
