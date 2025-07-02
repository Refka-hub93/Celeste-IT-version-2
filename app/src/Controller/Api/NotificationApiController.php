<?php

namespace App\Controller\Api;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api/notification', name: 'api_notification_', methods: ['GET'])]
class NotificationApiController extends AbstractController
{
    // #[Route('', name: 'list', methods: ['GET'])]
    // public function list(Request $request, NotificationRepository $notificationRepo): JsonResponse
    // {
    //     $tableId = $request->query->get('tableId');

    //     if (!$tableId) {
    //         return new JsonResponse(['error' => 'Paramètre "tableId" manquant'], 400);
    //     }

    //     $notifications = $notificationRepo->findBy(
    //         ['table' => $tableId],
    //         ['createdAt' => 'DESC']
    //     );

    //     $data = array_map(function ($notif) {
    //         return [
    //             'id' => $notif->getId(),
    //             'message' => $notif->getMessage(),
    //             'createdAt' => $notif->getCreatedAt()->format('Y-m-d H:i'),
    //         ];
    //     }, $notifications);

    //     return new JsonResponse($data);
    // }

// Route Symfony pour suppression



    /* --- liste JSON --- */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $req, NotificationRepository $repo): JsonResponse
    {
        $tableId = $req->query->get('tableId');
        if (!$tableId) return new JsonResponse(['error'=>'tableId manquant'],400);

        $notifs = $repo->findBy(['table'=>$tableId],['createdAt'=>'ASC']);
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




