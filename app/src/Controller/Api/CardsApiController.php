<?php
namespace App\Controller\Api;

use App\Entity\Cards;
use App\Entity\Columns;
use App\Repository\CardsRepository;
use App\Repository\ColumnsRepository;
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
        ColumnsRepository $columnsRepo
    ): JsonResponse {
        $data      = json_decode($request->getContent(), true);
        $title     = $data['cardTitle'] ?? null;
        $columnId  = $data['columns']   ?? null;   // id de colonne
        $desc      = $data['description'] ?? null;

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
            ->setColumns($column);

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

    /** Mise à jour */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        CardsRepository $cardsRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $card = $cardsRepo->find($id);
        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $card->setCardTitle($data['cardTitle'] ?? $card->getCardTitle())
             ->setDescription($data['description'] ?? $card->getDescription())
             ->setComment($data['comment'] ?? $card->getComment())
             ->setMembers($data['members'] ?? $card->getMembers())
             ->setAttachment($data['attachment'] ?? $card->getAttachment())
             ->setNotification($data['notification'] ?? $card->getNotification())
             ->setDeadline(
                 !empty($data['deadline'])
                     ? new \DateTime($data['deadline'])
                     : $card->getDeadline()
             );

        $em->flush();

        return new JsonResponse(['message' => 'Carte mise à jour']);
    }

    /** Suppression */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        CardsRepository $cardsRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $card = $cardsRepo->find($id);
        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        $em->remove($card);
        $em->flush();

        return new JsonResponse(['message' => 'Carte supprimée']);
    }
}
