<?php

namespace App\Controller;

use App\Entity\Tables; // Ton entité Tables
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class SubscribedController extends AbstractController
{
    // Affichage de la page de confirmation après le paiement réussi
    #[Route('/subscribed', name: 'app_subscribed')]
    public function confirmSubscription()
    {
        // Afficher une page de confirmation après le paiement réussi
        return $this->render('profil/subscribed.html.twig');
    }

    // Création du tableau
    #[Route('/table', name: 'app_table', methods: ['POST'])]
    public function createTable(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            // Récupérer les données du formulaire (titre)
            $title = $request->request->get('title');

            if (!$title) {
                throw new \Exception('Le titre est requis');
            }

            // Créer un nouveau tableau
            $table = new Tables();
            $table->setTableTitle($title);  // Assurez-vous que la méthode setTableTitle existe dans ton entité

            // Sauvegarder dans la base de données
            $entityManager->persist($table);
            $entityManager->flush();

            // Récupérer l'ID du tableau nouvellement créé
            $tableId = $table->getId();

            // Retourner une réponse JSON avec l'URL de redirection
            return new Response(
                json_encode(['success' => true, 'redirect_url' => $this->generateUrl('table', ['id' => $tableId])]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un message d'erreur en JSON
            return new Response(
                json_encode(['success' => false, 'error' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }
}
