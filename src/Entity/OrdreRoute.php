<?php

namespace App\Entity;

use App\Repository\OrdreRouteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdreRouteRepository::class)]
class OrdreRoute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $num_or = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_or = null;

    #[ORM\Column]
    private ?int $duree_deplacement = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_detenteur = null;

    #[ORM\Column(length: 20)]
    private ?string $fonction_detenteur = null;

    #[ORM\Column(length: 50)]
    private ?string $lieu_depart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_depart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_depart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumOr(): ?string
    {
        return $this->num_or;
    }

    public function setNumOr(string $num_or): static
    {
        $this->num_or = $num_or;

        return $this;
    }

    public function getDateOr(): ?\DateTimeInterface
    {
        return $this->date_or;
    }

    public function setDateOr(\DateTimeInterface $date_or): static
    {
        $this->date_or = $date_or;

        return $this;
    }

    public function getDureeDeplacement(): ?int
    {
        return $this->duree_deplacement;
    }

    public function setDureeDeplacement(int $duree_deplacement): static
    {
        $this->duree_deplacement = $duree_deplacement;

        return $this;
    }

    public function getNomDetenteur(): ?string
    {
        return $this->nom_detenteur;
    }

    public function setNomDetenteur(string $nom_detenteur): static
    {
        $this->nom_detenteur = $nom_detenteur;

        return $this;
    }

    public function getFonctionDetenteur(): ?string
    {
        return $this->fonction_detenteur;
    }

    public function setFonctionDetenteur(string $fonction_detenteur): static
    {
        $this->fonction_detenteur = $fonction_detenteur;

        return $this;
    }

    public function getLieuDepart(): ?string
    {
        return $this->lieu_depart;
    }

    public function setLieuDepart(string $lieu_depart): static
    {
        $this->lieu_depart = $lieu_depart;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getHeureDepart(): ?\DateTimeInterface
    {
        return $this->heure_depart;
    }

    public function setHeureDepart(\DateTimeInterface $heure_depart): static
    {
        $this->heure_depart = $heure_depart;

        return $this;
    }

}
