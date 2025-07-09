<?php

namespace App\Controller;


use App\Form\ContactForm;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EmailService $emailService): Response
    {
        // $contact = new Contact();
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dd($data);
            $context = [
                'sender' => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message']
            ];

            // ❗️ Ici, on utilise le service EmailService pour envoyer le mail
            // / Envoi du mail
            $emailService->send(
                $data['email'],
                'missaouirefka@gmail.com',
                $data['subject'],
                'contact',
                $context

            );
            // redirection ou message de confirmation

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact');
        }

        // ❗️ C’est cette ligne qui transmet le formulaire à Twig :
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
