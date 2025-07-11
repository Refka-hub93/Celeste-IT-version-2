<?php


namespace App\Service;

use App\Entity\Notification;
use App\Entity\Tables;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class ManageNotif
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Envoie une notification à un seul utilisateur
     */
    public function notifyUser(Users $user, Tables $table, string $message): void
    {
        $notif = new Notification();
        $notif->setUsers($user);
        $notif->setTables($table);
        $notif->setMessage($message);

        $this->em->persist($notif);
        $this->em->flush();
    }

    /**
     * Envoie une notification à tous les membres d’un tableau
     */
    public function notifyAllMembers(Tables $table, string $message): void
    {
        foreach ($table->getUsers() as $user) {
            $notif = new Notification();
            $notif->setUsers($user);
            $notif->setTables($table);
            $notif->setMessage($message);

            $this->em->persist($notif);
        }

        $this->em->flush();
    }
}
