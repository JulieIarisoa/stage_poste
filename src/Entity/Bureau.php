<?php

namespace App\Entity;

use App\Repository\BureauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BureauRepository::class)]
class Bureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $code_poste = null;

    #[ORM\Column(length: 20)]
    private ?string $adresse = null;

    #[ORM\Column(length: 109)]
    private ?string $telephone = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $matricule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodePoste(): ?int
    {
        return $this->code_poste;
    }

    public function setCodePoste(int $code_poste): static
    {
        $this->code_poste = $code_poste;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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
}
