<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $confirmationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $orderNumber = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    // #[ORM\Column(length: 100)]
    // private ?string $totalPrice = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $totalPrice = null;


    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Users $users = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfirmationDate(): ?\DateTime
    {
        return $this->confirmationDate;
    }

    public function setConfirmationDate(\DateTime $confirmationDate): static
    {
        $this->confirmationDate = $confirmationDate;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }


    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }
}
