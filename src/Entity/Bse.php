<?php

namespace App\Entity;

use App\Repository\BseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BseRepository::class)]
class Bse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $num_bse = null;

    #[ORM\Column(length: 30)]
    private ?string $destination = null;

    #[ORM\Column(length: 200)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_bse = null;

    #[ORM\Column]
    private ?int $depense_engage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumBse(): ?string
    {
        return $this->num_bse;
    }

    public function setNumBse(string $num_bse): static
    {
        $this->num_bse = $num_bse;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateBse(): ?\DateTimeInterface
    {
        return $this->date_bse;
    }

    public function setDateBse(\DateTimeInterface $date_bse): static
    {
        $this->date_bse = $date_bse;

        return $this;
    }

    public function getDepenseEngage(): ?int
    {
        return $this->depense_engage;
    }

    public function setDepenseEngage(int $depense_engage): static
    {
        $this->depense_engage = $depense_engage;

        return $this;
    }
}
