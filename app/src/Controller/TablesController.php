<?php
 
namespace App\Controller;

use App\Entity\Tables;
use App\Repository\TablesRepository;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TablesController extends AbstractController
{
    #[Route('/tables', name: 'app_tables')]
    #[IsGranted('ROLE_USER')] // protection par rôle utilisateur
    public function index(): Response
    {
        /**
         * @var Users $user
         */
        $user = $this->getUser();
        // Récupérer les tableaux de l'utilisateur connecté
        $mesTableaux = $user->getTables();

        return $this->render('tables/index.html.twig', [
            'tableaux' => $mesTableaux,
        ]);
    }

// Afficher un tableau spécifique avec l'ID
    // #[Route('tables/table/{id}', name: 'table')]
    // public function viewTable($id, EntityManagerInterface $entityManager)
    // {
    //     // Récupérer le tableau à partir de l'ID
    //     $table = $entityManager->getRepository(Tables::class)->find($id);

    //     if (!$table) {
    //         throw $this->createNotFoundException('Tableau non trouvé');
    //     }

    //     // Rendre la vue du tableau
    //     return $this->render('tables/view.html.twig', [
    //         'table' => $table,
    //     ]);
    // }

    #[Route('/tables/table', name: 'app_tables_table', methods: ['GET', 'POST'])]
    public function tableAdd(Request $request, EntityManagerInterface $em, UserInterface $user): Response
    {
        if ($request->isMethod('POST')) {
            $titre = $request->request->get('titre_du_tableau');

            if ($titre) {
                $tableau = new Tables();
                $tableau->setTableTitle($titre);
                $tableau->addUser($user);

                $em->persist($tableau);
                $em->flush();

                $this->addFlash('success', 'Tableau créé avec succès.');

                return $this->redirectToRoute('app_tables_table');
            }

            $this->addFlash('error', 'Le titre du tableau est requis.');
        }

        return $this->render('tables/table.html.twig');
    }


}




    // #[Route('/tables/table', name: 'app_tables')]

    // public function table(): Response
    // {

    //     return $this->render('tables/index.html.twig', [
    //         'controller_name' => 'TablesController',
    //     ]);

    // }









    // #[Route('/tables/ajouter', name: 'app_table_ajouter', methods: ['GET', 'POST'])]
    // public function tableAdd(Request $request, EntityManagerInterface $em, UserInterface $user): Response
    // {
    //     if ($request->isMethod('POST')) {
    //         $titre = $request->request->get('titre_du_tableau');

    //         if ($titre) {
    //             $tableau = new Tables();
    //             $tableau->setTableTitle($titre);
    //             $tableau->addUser($user);

    //             $em->persist($tableau);
    //             $em->flush();

    //             $this->addFlash('success', 'Tableau créé avec succès.');

    //             return $this->redirectToRoute('app_tables');
    //         }

    //         $this->addFlash('error', 'Le titre du tableau est requis.');
    //     }

    //     return $this->render('tables/table.html.twig');
    // }








