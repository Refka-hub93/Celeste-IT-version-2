<?php
namespace App\Controller\Api;

use App\Entity\Comments;
use App\Repository\CardsRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/comments')]
class CommentApiController extends AbstractController
{
    #[Route('/{cardId}', name: 'comment_list', methods: ['GET'])]
    public function list(CommentsRepository $commentRepo, int $cardId): JsonResponse
    {
        $comments = $commentRepo->findBy(['cards' => $cardId], ['createdAt' => 'ASC']);

        $data = array_map(fn($c) => [
            'id' => $c->getId(),
            'author' => $c->getUsers()->getEmail(),
            'content' => $c->getContent(),
            'createdAt' => $c->getCreatedAt()->format('Y-m-d H:i'),
        ], $comments);

        return new JsonResponse($data);
    }

    #[Route('/{cardId}', name: 'comment_add', methods: ['POST'])]
    public function add(
        int $cardId,
        Request $request,
        CardsRepository $cardsRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $card = $cardsRepo->find($cardId);
        if (!$card) {
            return new JsonResponse(['error' => 'Carte introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $content = $data['content'] ?? '';

        if (empty($content)) {
            return new JsonResponse(['error' => 'Contenu du commentaire vide'], 400);
        }

        $comment = new Comments();
        $comment->setCards($card);
        $comment->setUsers($this->getUser());
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $em->persist($comment);
        $em->flush();

        return new JsonResponse([
            'message' => 'Commentaire ajouté',
            'author' => $comment->getUsers()->getEmail(),
            'content' => $comment->getContent(),
            'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i'),
        ], 201);
    }
}
