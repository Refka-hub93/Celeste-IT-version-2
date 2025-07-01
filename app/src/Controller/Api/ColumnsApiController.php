<?php

namespace App\Controller\Api;

use App\Entity\Columns;
use App\Entity\Tables;
use App\Repository\ColumnsRepository;
use App\Repository\TablesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/columns', name: 'api_columns_')]
class ColumnsApiController extends AbstractController
{

#[Route('/api/columns', name: 'api_columns_index', methods: ['GET'])]
public function index(Request $request, ColumnsRepository $columnsRepo): JsonResponse
{
    $tableId = $request->query->get('tableId');

    if (!$tableId) {
        return new JsonResponse(['error' => 'Table ID is required'], 400);
    }

    $columns = $columnsRepo->findBy(['tables' => $tableId]);

    $data = [];
    foreach ($columns as $column) {
        $data[] = [
            'id' => $column->getId(),
            'title' => $column->getColumnTitle(),
            'ranking' => $column->getRanking()
        ];
    }

    return new JsonResponse($data);
}



    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, TablesRepository $tablesRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title    = $data['columnTitle'] ?? 'Nouvelle Colonne';
        $tableId  = $data['tables'] ?? null;
        $ranking  = $data['ranking'] ?? null;

        if (!$title || !$tableId) {
            return new JsonResponse(['error' => 'Le titre et l\'ID du tableau sont requis'], 400);
        }

        $table = $tablesRepo->find($tableId);
        if (!$table) {
            return new JsonResponse(['error' => 'Tableau introuvable'], 404);
        }

        $column = new Columns();
        $column->setColumnTitle($title);
        $column->setTables($table);
        $column->setRanking($ranking);

        $em->persist($column);
        $em->flush();

        return new JsonResponse([
            'message' => 'Colonne créée',
            'column' => [
                'id' => $column->getId(),
                'title' => $column->getColumnTitle(),
                'ranking' => $column->getRanking()
            ]
        ], 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request, ColumnsRepository $columnsRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $column = $columnsRepo->find($id);
        if (!$column) {
            return new JsonResponse(['error' => 'Colonne introuvable'], 404);
        }

        if (isset($data['columnTitle'])) {
            $column->setColumnTitle($data['columnTitle']);
        }

        if (isset($data['ranking'])) {
            $column->setRanking($data['ranking']);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Colonne mise à jour']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, ColumnsRepository $columnsRepo, EntityManagerInterface $em): JsonResponse
    {
        $column = $columnsRepo->find($id);
        if (!$column) {
            return new JsonResponse(['error' => 'Colonne introuvable'], 404);
        }

        $em->remove($column);
        $em->flush();

        return new JsonResponse(['message' => 'Colonne supprimée']);
    }
 

} 