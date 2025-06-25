<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationForm;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $user->setRoles(['ROLE_USER']); // 💡 Assure le rôle par défaut


            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // $context = [
            //     'firstname' => $user->getFirstname(),
            //     'lastname' => $user->getLastname(),
            //     'email' => $user->getEmail()
            // ];
            // $email = (new TemplatedEmail())
            //     ->from('demo@celeste-it.fr')
            //     ->to($user->getEmail())
            //     ->subject('Votre inscription sur notre site')
            //     ->htmlTemplate('emails/welcome.html.twig')
            //     ->context($context);

            // $mailer->send($email);

            $emailService->send(
                'celeste-it@celest-it.fr',                   // from
                $user->getEmail(),                           // to
                'Bienvenue chez Celeste-IT',                 // subject
                'welcome',                                   // template sans .html.twig
                [
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'userEmail' => $user->getEmail()
                ]
            );



            return $this->redirectToRoute('app_subscription');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
