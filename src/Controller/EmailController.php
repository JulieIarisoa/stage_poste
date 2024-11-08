<?php
// src/Controller/EmailController.php
namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    private $emailService;

    // Injection du service EmailService via le constructeur
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(): Response
    {
        $to = 'julieharisoa9@gmail.com'; // L'email du destinataire
        $subject = 'Test Email from Symfony';
        $body = 'This is a test email sent from Symfony!';

        $isSent = $this->emailService->sendEmail($to, $subject, $body);

        if ($isSent) {
            return new Response('Email sent successfully!');
        } else {
            return new Response('Failed to send email.');
        }
    }
}
