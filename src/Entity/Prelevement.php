<?php

namespace App\Entity;

use App\Repository\PrelevementRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: PrelevementRepository::class)]
class Prelevement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $datePrelevement = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $conductivite = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $turbidite = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $alcalinite = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $durete = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Emplacement $emplacement = null;

    #[ORM\ManyToOne(targetEntity: Preleveur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Preleveur $preleveur = null;

    #[ORM\ManyToOne(targetEntity: Analyseur::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Analyseur $analyseur = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypePh $typePh = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePrelevement(): ?DateTimeInterface
    {
        return $this->datePrelevement;
    }

    public function setDatePrelevement(DateTimeInterface $date): static
    {
        $this->datePrelevement = $date;
        return $this;
    }

    public function getValeurPh(): ?float
    {
        return $this->valeurPh;
    }

    public function setValeurPh(?float $valeurPh): static
    {
        $this->valeurPh = $valeurPh;
        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): static
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getPreleveur(): ?Preleveur
    {
        return $this->preleveur;
    }

    public function setPreleveur(?Preleveur $preleveur): static
    {
        $this->preleveur = $preleveur;
        return $this;
    }

    public function getAnalyseur(): ?Analyseur
    {
        return $this->analyseur;
    }

    public function setAnalyseur(?Analyseur $analyseur): static
    {
        $this->analyseur = $analyseur;
        return $this;
    }

    public function getTypePh(): ?TypePh
    {
        return $this->typePh;
    }

    public function setTypePh(?TypePh $typePh): static
    {
        $this->typePh = $typePh;
        return $this;
    }
}