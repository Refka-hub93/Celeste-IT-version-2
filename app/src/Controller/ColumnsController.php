<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Entity\Tables;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ColumnsController extends AbstractController
{
    // ✅ Formulaire pour ajouter une colonne liée à un tableau
    #[Route('/columns/new/{table}', name: 'app_column_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $tableId, EntityManagerInterface $em): Response
    {
        $table = $em->getRepository(Tables::class)->find($tableId);

        if (!$table) {
            throw $this->createNotFoundException('Tableau introuvable');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('column_title');

            if ($title) {
                $column = new Columns();
                $column->setColumnTitle($title);
                $column->setTables($table);

                $em->persist($column);
                $em->flush();

                $this->addFlash('success', 'Colonne ajoutée avec succès');
                return $this->redirectToRoute('app_table_show', ['id' => $tableId]);
            }

            $this->addFlash('error', 'Le titre est requis');
        }

        return $this->render('columns/new.html.twig', [
            'table' => $table
        ]);
    }

    // ✅ Page HTML d’une colonne (optionnelle)
    #[Route('/columns/{id}', name: 'app_column_show', methods: ['GET'])]
    public function show(Columns $column): Response
    {
        return $this->render('columns/show.html.twig', [
            'column' => $column
        ]);
    }

    // ✅ Suppression d'une colonne (formulaire ou lien HTML)
    #[Route('/columns/delete/{id}', name: 'app_column_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $column = $em->getRepository(Columns::class)->find($id);

        if (!$column) {
            $this->addFlash('error', 'Colonne introuvable');
            return $this->redirectToRoute('app_tables');
        }

        $tableId = $column->getTables()?->getId();

        $em->remove($column);
        $em->flush();

        $this->addFlash('success', 'Colonne supprimée');
        return $this->redirectToRoute('app_table_show', ['id' => $tableId]);
    }
}
