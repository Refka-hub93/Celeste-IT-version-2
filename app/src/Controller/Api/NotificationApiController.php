<?php

namespace App\Controller\Api;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api/notification', name: 'api_notification_', methods: ['GET'])]
class NotificationApiController extends AbstractController
{
   



    /* --- liste JSON --- */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $req, NotificationRepository $repo, UserInterface $userInterface ): JsonResponse
    {
        $tableId = $req->query->get('tableId');
        if (!$tableId) return new JsonResponse(['error'=>'tableId manquant'],400);

        $notifs = $repo->findByUserAndTable($userInterface, $tableId);
        $data = array_map(fn($n)=>[
            'id'=>$n->getId(),
            'message'=>$n->getMessage(),
            'createdAt'=>$n->getCreatedAt()->format('Y-m-d H:i'),
        ], $notifs);

        return new JsonResponse($data);
    }

    /* --- delete 1 --- */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, NotificationRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $n = $repo->find($id);
        if(!$n) return new JsonResponse(['error'=>'Notif introuvable'],404);
        $em->remove($n); $em->flush();
        return new JsonResponse(['message'=>'Notification supprimée']);
    }

    /* --- delete ALL for a table --- */
    #[Route('', name: 'delete_all', methods: ['DELETE'])]
    public function deleteAll(Request $req, NotificationRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $tableId = $req->query->get('tableId');
        if(!$tableId) return new JsonResponse(['error'=>'tableId manquant'],400);

        $notifs = $repo->findBy(['table'=>$tableId]);
        foreach ($notifs as $n) $em->remove($n);
        $em->flush();

        return new JsonResponse(['message'=>'Toutes les notifications supprimées']);
    }

    
}




