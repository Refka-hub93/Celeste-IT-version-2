<?php

namespace App\Entity;

use App\Repository\ColumnsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColumnsRepository::class)]
class Columns
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $columnTitle = null;

    #[ORM\Column(nullable: true)]
    private ?int $rank = null;

    #[ORM\ManyToOne(inversedBy: 'columns')]
    private ?Tables $tables = null;

    /**
     * @var Collection<int, Cards>
     */
    #[ORM\OneToMany(targetEntity: Cards::class, mappedBy: 'columns')]
    private Collection $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColumnTitle(): ?string
    {
        return $this->columnTitle;
    }

    public function setColumnTitle(string $columnTitle): static
    {
        $this->columnTitle = $columnTitle;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getTables(): ?Tables
    {
        return $this->tables;
    }

    public function setTables(?Tables $tables): static
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * @return Collection<int, Cards>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Cards $card): static
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setColumns($this);
        }

        return $this;
    }

    public function removeCard(Cards $card): static
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getColumns() === $this) {
                $card->setColumns(null);
            }
        }

        return $this;
    }
}
