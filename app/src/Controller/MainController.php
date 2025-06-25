<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

     #[Route('/mentions', name: 'app_mentions')]
    public function legales(): Response
    {
        return $this->render('main/mentions.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    
     #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('main/cgv.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    
     #[Route('/features', name: 'app_features')]
    public function features(): Response
    {
        return $this->render('main/features.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    
     #[Route('/confidentialite', name: 'app_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('main/confidentialite.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
