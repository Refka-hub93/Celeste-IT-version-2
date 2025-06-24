<?php

namespace App\Entity;

use App\Repository\CardsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Columns;

#[ORM\Entity(repositoryClass: CardsRepository::class)]
class Cards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $cardTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $comments = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(length: 50)]
    private ?string $members = null;

    #[ORM\Column(length: 255)]
    private ?string $notifications = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $attachement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $deadline = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardTitle(): ?string
    {
        return $this->cardTitle;
    }

    public function setCardTitle(string $cardTitle): static
    {
        $this->cardTitle = $cardTitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getMembers(): ?string
    {
        return $this->members;
    }

    public function setMembers(string $members): static
    {
        $this->members = $members;

        return $this;
    }

    public function getNotifications(): ?string
    {
        return $this->notifications;
    }

    public function setNotifications(string $notifications): static
    {
        $this->notifications = $notifications;

        return $this;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(string $attachement): static
    {
        $this->attachement = $attachement;

        return $this;
    }

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTime $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }
}
