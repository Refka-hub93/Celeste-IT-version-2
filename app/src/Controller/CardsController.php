<?php

namespace App\Controller;

use App\Entity\Cards;
use App\Entity\Columns;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/cards', name: 'app_card_')]
class CardsController extends AbstractController
{
    /** Formulaire d’ajout d’une carte dans une colonne */
    #[Route('/new/{columnId}', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $columnId,
        EntityManagerInterface $em,
       
    ): Response {
        // Récupération d’une colonne cible avant ajout d’une carte
        $column = $em->getRepository(Columns::class)->find($columnId);
        if (!$column) {
            throw $this->createNotFoundException('Colonne introuvable');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('card_title');

             

            if ($title) {
                $card = new Cards();
                $card->setCardTitle($title)
                    ->setColumns($column);


               $em->persist($card);
                $em->flush();

                $this->addFlash('success', 'Carte ajoutée');
                return $this->redirectToRoute(
                    'app_column_show',
                    ['id' => $columnId]
                );
            }
            $this->addFlash('error', 'Le titre est requis');
        }

        return $this->render('cards/new.html.twig', ['column' => $column]);
    }

    /** Affichage d’une carte (optionnel) */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Cards $card): Response
    {
        return $this->render('cards/show.html.twig', ['card' => $card]);
    }

    /** Suppression (via formulaire ou lien) */
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        int $id,
        EntityManagerInterface $em
    ): Response {
        $card = $em->getRepository(Cards::class)->find($id);
        if (!$card) {
            $this->addFlash('error', 'Carte introuvable');
            return $this->redirectToRoute('app_tables');
        }

        $columnId = $card->getColumns()->getId();
        $em->remove($card);
        $em->flush();

        $this->addFlash('success', 'Carte supprimée');
        return $this->redirectToRoute('app_column_show', ['id' => $columnId]);
    }
}
