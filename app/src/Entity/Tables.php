<?php

namespace App\Entity;

use App\Repository\TablesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TablesRepository::class)]
class Tables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $members = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'tables')]
    private Collection $users;

    /**
     * @var Collection<int, Columns>
     */
    #[ORM\OneToMany(targetEntity: Columns::class, mappedBy: 'tables')]
    private Collection $columns;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'tables')]
    private Collection $notification;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->columns = new ArrayCollection();
        $this->notification = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTable($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTable($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Columns>
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addColumn(Columns $column): static
    {
        if (!$this->columns->contains($column)) {
            $this->columns->add($column);
            $column->setTables($this);
        }

        return $this;
    }

    public function removeColumn(Columns $column): static
    {
        if ($this->columns->removeElement($column)) {
            // set the owning side to null (unless already changed)
            if ($column->getTables() === $this) {
                $column->setTables(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notification->contains($notification)) {
            $this->notification->add($notification);
            $notification->setTables($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notification->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getTables() === $this) {
                $notification->setTables(null);
            }
        }

        return $this;
    }



    
}
