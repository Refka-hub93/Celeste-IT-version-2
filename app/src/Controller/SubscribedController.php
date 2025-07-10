<?php

namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
use Symfony\Component\Routing\Annotation\Route;

final class SubscribedController extends AbstractController
{
    // Affichage de la page de confirmation après le paiement réussi
    #[Route('/subscribed', name: 'app_subscribed')]
    public function confirmSubscription()
    {
        // Afficher une page de confirmation après le paiement réussi
        return $this->render('profil/subscribed.html.twig');
    }

}
