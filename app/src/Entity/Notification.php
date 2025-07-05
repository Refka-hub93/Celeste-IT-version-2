<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column(length: 100)]
    // private ?string $targetUsers = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $targetUsers = null;

 


    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Tables $table = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

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

    public function getTargetUsers(): ?string
    {
        return $this->targetUsers;
    }

    public function setTargetUsers(string $targetUsers): static
    {
        $this->targetUsers = $targetUsers;

        return $this;
    }

    public function getTables(): ?Tables
    {
        return $this->table;
    }

    public function setTables(?Tables $tables): static
    {
        $this->table = $tables;

        return $this;
    }
}
