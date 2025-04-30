<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreleveurRepository::class)]
class Client extends Utilisateur
{
    
}
