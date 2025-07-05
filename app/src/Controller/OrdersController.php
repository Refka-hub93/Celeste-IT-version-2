<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class OrdersController extends AbstractController
{


#[Route('/mes-commandes', name: 'app_orders')]
public function list(Security $security, OrdersRepository $ordersRepo): Response
{
    $user = $security->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $orders = $ordersRepo->findBy(['users' => $user], ['confirmationDate' => 'DESC']);

    return $this->render('orders/index.html.twig', [
        'orders' => $orders,
    ]);
}

    #[Route('/orders/create', name: 'orders_create')]
    public function createOrder(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        // ✅ Sécurité : l'utilisateur doit être connecté
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // ✅ Définir le prix (en euros)
        $prix = 49.00;

        // ✅ Créer la commande
        $order = new Orders();
        $order->setUsers($user);
        $order->setOrderNumber(uniqid('order_'));
        $order->setStatus('pending');
        $order->setConfirmationDate(new \DateTime());
        $order->setTotalPrice($prix);

        $em->persist($order);
        $em->flush();

        // ✅ Créer la session Stripe
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);

        $checkoutSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Abonnement annuel Celeste-IT'
                    ],
                    'unit_amount' => (int) ($prix * 100), // montant en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('orders_success', [
                'id' => $order->getId()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('orders_cancel', [
                'id' => $order->getId()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'metadata' => [
                'order_id' => $order->getId()
            ],
        ]);

        return $this->redirect($checkoutSession->url);
    }

    #[Route('/orders/success/{id}', name: 'orders_success')]
    public function success(Orders $order, EntityManagerInterface $em): Response
    {
        if ($order->getStatus() !== 'paid') {
            $order->setStatus('paid');
            $em->flush();
        }

        return $this->render('orders/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/cancel/{id}', name: 'orders_cancel')]
    public function cancel(Orders $order): Response
    {

//  $order->setMessage;

        return $this->render('orders/cancel.html.twig', [
            'order' => $order,
        ]);
    }
}
