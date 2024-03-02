<?php

namespace App\Controller;

// src/Controller/MailerController.php


use Symfony\Component\Mailer\Exception\TransportExceptionInterface ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;

class MailerController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
            $email = (new Email())
                ->from('slim.bentanfous@esprit.tn')
                ->to('you@example.com')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);

            // Rediriger vers la page d'accueil après l'envoi du formulaire
            return $this->render('Mailer/index.html.twig');

            // Gérer les erreurs d'envoi de l'e-mail



        // ...

    }
}
