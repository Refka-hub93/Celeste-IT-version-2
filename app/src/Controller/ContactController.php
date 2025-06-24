<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setStatus('en attente'); // valeur par défaut au moment de la soumission

            $entityManager->persist($contact);
            $entityManager->flush();


            // redirection ou message de confirmation

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact');
        }

        // ❗️ C’est cette ligne qui transmet le formulaire à Twig :
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);


        return $this->render('contact/index.html.twig', compact('contact')
        );
    }
}
