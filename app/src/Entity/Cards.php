<?php

namespace App\Entity;

use App\Repository\CardsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardsRepository::class)]
class Cards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $cardTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $members = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notification = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $attachement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $deadline = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    private ?Columns $columns = null;

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

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

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getMembers(): ?string
    {
        return $this->members;
    }

    public function setMembers(?string $members): static
    {
        $this->members = $members;

        return $this;
    }

    public function getNotification(): ?string
    {
        return $this->notification;
    }

    public function setNotification(?string $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(?string $attachement): static
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

    public function getColumns(): ?Columns
    {
        return $this->columns;
    }

    public function setColumns(?Columns $columns): static
    {
        $this->columns = $columns;

        return $this;
    }
}
