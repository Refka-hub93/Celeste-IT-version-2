<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]

    public function index(NotificationRepository $notificationRepo): Response
    {
        $notifications = $notificationRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    
}
