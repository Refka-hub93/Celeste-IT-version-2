<?php

namespace App\Controller;

use App\Entity\Users;

use App\Entity\Tables;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')] // Toutes les routes ici nécessitent un utilisateur connecté
final class TablesController extends AbstractController
{
    
    // ✅ Page web avec affichage des tableaux de l'utilisateur
    #[Route('/tables', name: 'app_tables', methods: ['GET'])]
    public function index(UserInterface $user): Response
    {
        // Vérification du chargement de l'utilisateur
        if (!$user instanceof Users) {
            throw new \LogicException('Mauvais type d’utilisateur');
        }

        $mesTableaux = $user->getTables(); // devrait contenir les tableaux liés

        return $this->render('tables/index.html.twig', [
            'tableaux' => $mesTableaux,
        ]);
    }

    #[Route('/tables/addTable', name: 'app_tables_addTable', methods: ['GET', 'POST'])]
    public function tableAdd(Request $request, EntityManagerInterface $entityManagerInterface, UserInterface $user): Response
    {


        $tableau = null;

        if ($request->isMethod('POST')) {
            $titre = $request->request->get('titre_du_tableau');

            if ($titre) {
                $tableau = new Tables();
                $tableau->setTitle($titre);
                $tableau->addUser($user);

                $entityManagerInterface->persist($tableau);
                $entityManagerInterface->flush();

                $this->addFlash('success', 'Tableau créé avec succès.');
            } else {
                $this->addFlash('error', 'Le titre du tableau est requis.');
            }
        }

        return $this->render('tables/addTable.html.twig', [
            'tableau' => $tableau
        ]);
    }


    #[Route('/tables/{id}', name: 'app_tables_show', methods: ['GET'])]
    public function showTable(Tables $tableau): Response
    {
        return $this->render('tables/show.html.twig', [
            'tableau' => $tableau,
        ]);
    }














    // ✅ API pour créer un tableau 
    #[Route('/api/tables', name: 'api_table_create', methods: ['POST'])]
    public function createTableApi(Request $request, EntityManagerInterface $em, UserInterface $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || empty($data['title'])) {
            return new JsonResponse(['error' => 'Le titre est requis'], Response::HTTP_BAD_REQUEST);
        }

        $tableau = new Tables();
        $tableau->setTitle($data['title']);
        $tableau->adduser($user);

        $em->persist($tableau);
        $em->flush();

        return new JsonResponse([
            'message' => 'Tableau créé avec succès',
            'tableau' => [
                'id' => $tableau->getId(),
                'title' => $tableau->getTitle(),
            ]
        ], Response::HTTP_CREATED);
    }

    // ✅ API pour récupérer tous les tableaux de l'utilisateur connecté
    #[Route('/api/tables', name: 'api_table_list', methods: ['GET'])]
    public function listTablesApi(Users $user): JsonResponse
    {
        $tableaux = $user->getTables();

        $data = [];
        foreach ($tableaux as $tableau) {
            $data[] = [
                'id' => $tableau->getId(),
                'title' => $tableau->getTitle(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
