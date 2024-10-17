<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_payement = null;

    #[ORM\Column]
    private ?int $taux_payer = null;

    #[ORM\Column(length: 30)]
    private ?string $nom_bp = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_chef_agence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePayement(): ?\DateTimeInterface
    {
        return $this->date_payement;
    }

    public function setDatePayement(\DateTimeInterface $date_payement): static
    {
        $this->date_payement = $date_payement;

        return $this;
    }

    public function getTauxPayer(): ?int
    {
        return $this->taux_payer;
    }

    public function setTauxPayer(int $taux_payer): static
    {
        $this->taux_payer = $taux_payer;

        return $this;
    }

    public function getNomBp(): ?string
    {
        return $this->nom_bp;
    }

    public function setNomBp(string $nom_bp): static
    {
        $this->nom_bp = $nom_bp;

        return $this;
    }

    public function getNomChefAgence(): ?string
    {
        return $this->nom_chef_agence;
    }

    public function setNomChefAgence(string $nom_chef_agence): static
    {
        $this->nom_chef_agence = $nom_chef_agence;

        return $this;
    }
}
