<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Users; // si tu utilises une entité Users
use Symfony\Component\HttpFoundation\Response;

class ExempleController extends AbstractController
{
    public function index(MailerInterface $mailer): Response
    {
        // Exemple : simulateur d'utilisateur avec un email
        $user = new Users(); // remplace ceci par ton vrai utilisateur (issu de la BDD)
        $user->setEmail('utilisateur@example.com');

        // Création de l'email
        $email = (new Email())
            ->from('no-reply@monsite.com') // ⚠️ `$form` n'existe pas ici
            ->to($user->getEmail())       // ⚠️ `get(Users)` est invalide
            ->subject('Bienvenue !')
            ->text('Merci de votre inscription !');

        // Envoi
        $mailer->send($email);

        return new Response('Email envoyé avec succès.');
    }
}
