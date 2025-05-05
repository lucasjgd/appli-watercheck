<?php

namespace App\Entity;

use App\Repository\PreleveurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreleveurRepository::class)]
class Admin extends Utilisateur
{
    
}
