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

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $destination = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_bse = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree_mission = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom_detenteur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom_detenteur = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fonction_detenteur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lieu_depart_missionnaire = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heure_depart_missionnaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_depart_missionnaire = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $lieu_bse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom_chaufeur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom_chafeur = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tel_transporteur = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat_payment_or = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat_payment_bst = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat_validation_or = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat_validation_bst = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postale_payement_or = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postale_payment_bst = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $depense_bst = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateBse(): ?\DateTimeInterface
    {
        return $this->date_bse;
    }

    public function setDateBse(?\DateTimeInterface $date_bse): static
    {
        $this->date_bse = $date_bse;

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

    public function getDureeMission(): ?int
    {
        return $this->duree_mission;
    }

    public function setDureeMission(?int $duree_mission): static
    {
        $this->duree_mission = $duree_mission;

        return $this;
    }

    public function getNomDetenteur(): ?string
    {
        return $this->nom_detenteur;
    }

    public function setNomDetenteur(?string $nom_detenteur): static
    {
        $this->nom_detenteur = $nom_detenteur;

        return $this;
    }

    public function getPrenomDetenteur(): ?string
    {
        return $this->prenom_detenteur;
    }

    public function setPrenomDetenteur(?string $prenom_detenteur): static
    {
        $this->prenom_detenteur = $prenom_detenteur;

        return $this;
    }

    public function getFonctionDetenteur(): ?string
    {
        return $this->fonction_detenteur;
    }

    public function setFonctionDetenteur(?string $fonction_detenteur): static
    {
        $this->fonction_detenteur = $fonction_detenteur;

        return $this;
    }

    public function getLieuDepartMissionnaire(): ?string
    {
        return $this->lieu_depart_missionnaire;
    }

    public function setLieuDepartMissionnaire(?string $lieu_depart_missionnaire): static
    {
        $this->lieu_depart_missionnaire = $lieu_depart_missionnaire;

        return $this;
    }

    public function getHeureDepartMissionnaire(): ?\DateTimeInterface
    {
        return $this->heure_depart_missionnaire;
    }

    public function setHeureDepartMissionnaire(?\DateTimeInterface $heure_depart_missionnaire): static
    {
        $this->heure_depart_missionnaire = $heure_depart_missionnaire;

        return $this;
    }

    public function getDateDepartMissionnaire(): ?\DateTimeInterface
    {
        return $this->date_depart_missionnaire;
    }

    public function setDateDepartMissionnaire(?\DateTimeInterface $date_depart_missionnaire): static
    {
        $this->date_depart_missionnaire = $date_depart_missionnaire;

        return $this;
    }

    public function getLieuBse(): ?string
    {
        return $this->lieu_bse;
    }

    public function setLieuBse(?string $lieu_bse): static
    {
        $this->lieu_bse = $lieu_bse;

        return $this;
    }

    public function getNomChaufeur(): ?string
    {
        return $this->nom_chaufeur;
    }

    public function setNomChaufeur(?string $nom_chaufeur): static
    {
        $this->nom_chaufeur = $nom_chaufeur;

        return $this;
    }

    public function getPrenomChafeur(): ?string
    {
        return $this->prenom_chafeur;
    }

    public function setPrenomChafeur(?string $prenom_chafeur): static
    {
        $this->prenom_chafeur = $prenom_chafeur;

        return $this;
    }

    public function getTelTransporteur(): ?string
    {
        return $this->tel_transporteur;
    }

    public function setTelTransporteur(?string $tel_transporteur): static
    {
        $this->tel_transporteur = $tel_transporteur;

        return $this;
    }

    public function getEtatPaymentOr(): ?string
    {
        return $this->etat_payment_or;
    }

    public function setEtatPaymentOr(?string $etat_payment_or): static
    {
        $this->etat_payment_or = $etat_payment_or;

        return $this;
    }

    public function getEtatPaymentBst(): ?string
    {
        return $this->etat_payment_bst;
    }

    public function setEtatPaymentBst(?string $etat_payment_bst): static
    {
        $this->etat_payment_bst = $etat_payment_bst;

        return $this;
    }

    public function getEtatValidationOr(): ?string
    {
        return $this->etat_validation_or;
    }

    public function setEtatValidationOr(?string $etat_validation_or): static
    {
        $this->etat_validation_or = $etat_validation_or;

        return $this;
    }

    public function getEtatValidationBst(): ?string
    {
        return $this->etat_validation_bst;
    }

    public function setEtatValidationBst(?string $etat_validation_bst): static
    {
        $this->etat_validation_bst = $etat_validation_bst;

        return $this;
    }

    public function getCodePostalePayementOr(): ?int
    {
        return $this->code_postale_payement_or;
    }

    public function setCodePostalePayementOr(?int $code_postale_payement_or): static
    {
        $this->code_postale_payement_or = $code_postale_payement_or;

        return $this;
    }

    public function getCodePostalePaymentBst(): ?int
    {
        return $this->code_postale_payment_bst;
    }

    public function setCodePostalePaymentBst(?int $code_postale_payment_bst): static
    {
        $this->code_postale_payment_bst = $code_postale_payment_bst;

        return $this;
    }

    public function getDepenseBst(): ?string
    {
        return $this->depense_bst;
    }

    public function setDepenseBst(?string $depense_bst): static
    {
        $this->depense_bst = $depense_bst;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
