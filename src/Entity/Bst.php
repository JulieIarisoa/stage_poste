<?php

namespace App\Entity;

use App\Repository\BstRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BstRepository::class)]
class Bst
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $num_bst = null;

    #[ORM\Column(length: 8)]
    private ?string $id_transport = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_bst = null;

    #[ORM\Column(length: 50)]
    private ?string $lieu_bst = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_transporteur = null;

    #[ORM\Column(length: 12)]
    private ?string $telephone_transport = null;

    #[ORM\Column]
    private ?bool $valide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumBst(): ?string
    {
        return $this->num_bst;
    }

    public function setNumBst(string $num_bst): static
    {
        $this->num_bst = $num_bst;

        return $this;
    }

    public function getIdTransport(): ?string
    {
        return $this->id_transport;
    }

    public function setIdTransport(string $id_transport): static
    {
        $this->id_transport = $id_transport;

        return $this;
    }

    public function getDateBst(): ?\DateTimeInterface
    {
        return $this->date_bst;
    }

    public function setDateBst(\DateTimeInterface $date_bst): static
    {
        $this->date_bst = $date_bst;

        return $this;
    }

    public function getLieuBst(): ?string
    {
        return $this->lieu_bst;
    }

    public function setLieuBst(string $lieu_bst): static
    {
        $this->lieu_bst = $lieu_bst;

        return $this;
    }

    public function getNomTransporteur(): ?string
    {
        return $this->nom_transporteur;
    }

    public function setNomTransporteur(string $nom_transporteur): static
    {
        $this->nom_transporteur = $nom_transporteur;

        return $this;
    }

    public function getTelephoneTransport(): ?string
    {
        return $this->telephone_transport;
    }

    public function setTelephoneTransport(string $telephone_transport): static
    {
        $this->telephone_transport = $telephone_transport;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }
}
