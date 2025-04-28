<?php

namespace App\Entity;

use App\Repository\AnalyseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalyseurRepository::class)]
class Analyseur extends Utilisateur
{
    
}
