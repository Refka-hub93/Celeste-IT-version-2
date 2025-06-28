<?php

namespace App\Entity;

use App\Repository\AssocierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AssocierRepository::class)]
#[UniqueEntity(fields: ['utilisateur', 'tableau'], message: 'Cet utilisateur est déjà membre de ce tableau.')]

class Associer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Tables::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tables $tableau = null;

    #[ORM\Column(length: 20)]
    private string $fonction = 'membre';

    // Getters & setters...
}
