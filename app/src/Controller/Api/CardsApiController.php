<?php

namespace App\Controller\Api;

use App\Entity\Cards;
use App\Entity\Columns;
use App\Entity\Notification;
use App\Repository\CardsRepository;
use App\Repository\ColumnsRepository;
use App\Service\ManageNotif;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/cards', name: 'api_cards_')]
class CardsApiController extends AbstractController
{
    /** Création d’une carte */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ColumnsRepository $columnsRepo, ManageNotif $manageNotif
    ): JsonResponse {
        $data      = json_decode($request->getContent(), true);
        $title     = $data['cardTitle'] ?? null;
        $columnId  = $data['columns']   ?? null;   // id de colonne
        $desc      = $data['description'] ?? null;

        $attachment = $data['attachment'] ?? null;
        $deadline = !empty($data['deadline']) ? new \DateTime($data['deadline']) : null;

        if (!$title || !$columnId) {
            return new JsonResponse(
                ['error' => 'Titre et ID de colonne requis'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $column = $columnsRepo->find($columnId);
        if (!$column) {
            return new JsonResponse(
                ['error' => 'Colonne introuvable'],
                Response::HTTP_NOT_FOUND
            );
        }

        $card = (new Cards())
            ->setCardTitle($title)
            ->setDescription($desc)

            ->setAttachment($attachment)
            ->setDeadline($deadline)
            ->setColumns($column);

        //  Envoyer à tous les membres

     $manageNotif->notifyAllMembers($card->getColumns()->getTables(), "Une carte a été créée" . $card->getCardTitle());


        $em->persist($card);

        $em->flush();

        return new JsonResponse(
            [
                'message' => 'Carte créée',
                'card' => [
                    'id'    => $card->getId(),
                    'title' => $card->getCardTitle(),
                ],
            ],
            Response::HTTP_CREATED
        );
    }


    /** Récupération d'une carte par son ID */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        int $id,
        CardsRepository $cardsRepo
    ): JsonResponse {
        $card = $cardsRepo->find($id);

        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        return new JsonResponse([
            'id' => $card->getId(),
            'title' => $card->getCardTitle(),
            'description' => $card->getDescription(),

            'attachment' => $card->getAttachment(),
            'deadline' => $card->getDeadline()?->format('Y-m-d H:i:s'),
            'column_id' => $card->getColumns()->getId()
        ], 200);
    }





    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        CardsRepository $cardsRepo,
        EntityManagerInterface $em,
        ManageNotif $manageNotif
    ): JsonResponse {
        $card = $cardsRepo->find($id);
        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);

        //  NOUVELLE PARTIE pour le drag & drop (changement de colonne)
        if (isset($data['columns'])) {
            $newColumn = $em->getRepository(Columns::class)->find($data['columns']);
            if ($newColumn) {
                $card->setColumns($newColumn);

             $manageNotif->notifyAllMembers($card->getColumns()->getTables(), "Une carte a été déplacée" . $card->getCardTitle());

            }
        }

        // Ton ancien code conservé tel quel :
        $card->setCardTitle($data['cardTitle'] ?? $card->getCardTitle())
            ->setDescription($data['description'] ?? $card->getDescription())
            // ->setComment($data['comment'] ?? $card->getComment())
            ->setAttachment($data['attachment'] ?? $card->getAttachment())

            ->setDeadline(
                !empty($data['deadline'])
                    ? new \DateTime($data['deadline'])
                    : $card->getDeadline()
            );

        
        // ✅ Envoyer à tous les membres
  $manageNotif->notifyAllMembers($card->getColumns()->getTables(), "Carte mise à jour" . $card->getCardTitle());

        $em->persist($card);
        $em->flush();

        return new JsonResponse(['message' => 'Carte mise à jour']);
    }





    /** Suppression */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        CardsRepository $cardsRepo,
        EntityManagerInterface $em,
        ManageNotif $manageNotif
    ): JsonResponse {
        $card = $cardsRepo->find($id);
        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        // Création de la notification avant la suppression
      
  $manageNotif->notifyAllMembers($card->getColumns()->getTables(), "Une carte a été supprimée" . $card->getCardTitle());
        $em->remove($card);
        $em->flush();

        return new JsonResponse(['message' => 'Carte supprimée']);
    }

    // src/Controller/Api/CardsApiController.php

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, CardsRepository $cardsRepo): JsonResponse
    {
        $columnId = $request->query->get('columnId');
        if (!$columnId) {
            return new JsonResponse(['error' => 'columnId requis'], 400);
        }

        $cards = $cardsRepo->findBy(['columns' => $columnId]);
        $data  = array_map(fn(Cards $c) => [
            'id' => $c->getId(),
            'title' => $c->getCardTitle(),
            'description' => $c->getDescription(),

            'attachment' => $c->getAttachment(),
            'deadline' => $c->getDeadline()?->format('Y-m-d\TH:i'),
        ], $cards);

        return new JsonResponse($data);
    }
}
