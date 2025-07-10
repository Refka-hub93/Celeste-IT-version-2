<?php

namespace App\Controller;

use App\Form\PasswordForgottenForm;
use App\Form\ResetPasswordForm;
use App\Repository\UsersRepository;
use App\Service\EmailService;
use App\Service\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/resetPassword', name: 'app_reset_password')]
    public function resetPassword( Request $request, UsersRepository $usersRepository,
    JWTService $jwt, EmailService $emailService)  : Response{
         
         $form = $this->createForm(ResetPasswordForm::class);
            // Handle the form 
            // je reccupére le contenu du form 
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            // Si l'utilisateur existe, on génère un token     

            // If the user does not exist, we do not reveal it  
            if ($user) {
                 // Génération de token

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            $payload = [
                'user_id' => $user->getId(),
            ];

            // On génère le token

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            
                // On génère l'URL vers reset_password
                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);


            $emailService->send(
                'celeste-it@celest-it.fr',                   // from
                $user->getEmail(),                           // to
                'Récupération de mot de passe',                 // subject
                'reset-pass',                                   
                compact('user', 'url') 
            );
            
      


            // On redirige l'utilisateur vers la page de connexion avec un message de succès
                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');

            }


            // Si l'utilisateur n'existe pas, on ne révèle pas son existence
            // On redirige l'utilisateur vers la page de connexion avec un message d'erreur
     $this->addFlash('error', 'un problème est survenu');
                
                return $this->redirectToRoute('app_login');
        }

            // vue twig
        return $this->render('security/reset_password.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    

}

// requirements: ['token' => '.+'] indique à Symfony d'accepter n'importe quel caractère, y compris les . et _ dans {token}.
    #[Route('/resetPassword/{token}', name: 'reset_password', requirements: ['token' => '.+'])]
    public function resetPasswordToken(
        $token,
        JWTService $jwt,
        UsersRepository $usersRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response
    {
        
        // On vérifie si le token est valide (cohérent, pas expiré et signature correcte)
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // Le token est valide
            // On récupère les données (payload)
            $payload = $jwt->getPayload($token);
            
            
            // On récupère le user
            $user = $usersRepository->find($payload['user_id']);

            if($user){
                $form = $this->createForm(PasswordForgottenForm::class);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $user->setPassword(
                        $passwordHasher->hashPassword($user, $form->get('password')->getData())
                    );

                    $em->flush();

                    $this->addFlash('success', 'Mot de passe changé avec succès');
                    return $this->redirectToRoute('app_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'passForm' => $form->createView()
                ]);
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }
}
