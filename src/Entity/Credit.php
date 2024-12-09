<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditRepository::class)]
class Credit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_renouvellement = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $credit_initial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRenouvellement(): ?\DateTimeInterface
    {
        return $this->date_renouvellement;
    }

    public function setDateRenouvellement(\DateTimeInterface $date_renouvellement): static
    {
        $this->date_renouvellement = $date_renouvellement;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCreditInitial(): ?string
    {
        return $this->credit_initial;
    }

    public function setCreditInitial(?string $credit_initial): static
    {
        $this->credit_initial = $credit_initial;

        return $this;
    }
}
