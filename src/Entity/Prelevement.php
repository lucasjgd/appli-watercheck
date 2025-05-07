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

    #[ORM\Column(type: 'date')]
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

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]  
    private ?Utilisateur $preleveur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Utilisateur $analyseur = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypePH $typePh = null;


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

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): static
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getPreleveur(): ?Utilisateur
    {
        return $this->preleveur;
    }

    public function setPreleveur(?Utilisateur $preleveur): static
    {
        $this->preleveur = $preleveur;
        return $this;
    }

    public function getAnalyseur(): ?Utilisateur
    {
        return $this->analyseur;
    }

    public function setAnalyseur(?Utilisateur $analyseur): static
    {
        $this->analyseur = $analyseur;
        return $this;
    }

    public function getTypePh(): ?TypePH
    {
        return $this->typePh;
    }

    public function setTypePh(?TypePH $typePh): static
    {
        $this->typePh = $typePh;
        return $this;
    }

    public function getConductivite(): ?float
    {
        return $this->conductivite;
    }

    public function setConductivite(?float $conductivite): static
    {
        $this->conductivite = $conductivite;
        return $this;
    }

    public function getTurbidite(): ?float
    {
        return $this->turbidite;
    }

    public function setTurbidite(?float $turbidite): static
    {
        $this->turbidite = $turbidite;
        return $this;
    }

    public function getAlcalinite(): ?float
    {
        return $this->alcalinite;
    }

    public function setAlcalinite(?float $alcalinite): static
    {
        $this->alcalinite = $alcalinite;
        return $this;
    }

    public function getDurete(): ?float
    {
        return $this->durete;
    }

    public function setDurete(?float $durete): static
    {
        $this->durete = $durete;
        return $this;
    }
}