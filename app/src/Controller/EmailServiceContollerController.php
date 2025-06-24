<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmailServiceContollerController extends AbstractController
{
    #[Route('/email/service/contoller', name: 'app_email_service_contoller')]
    public function index(): Response
    {
        return $this->render('email_service_contoller/index.html.twig', [
            'controller_name' => 'EmailServiceContollerController',
        ]);
    }
}
