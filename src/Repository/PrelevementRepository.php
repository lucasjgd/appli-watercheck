<?php

namespace App\Repository;

use App\Entity\Prelevement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;

/**
 * @extends ServiceEntityRepository<Prelevement>
 */
class PrelevementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prelevement::class);
    }

    public function estAnalyse(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.conductivite IS NOT NULL')
            ->andWhere('p.turbidite IS NOT NULL')
            ->andWhere('p.alcalinite IS NOT NULL')
            ->andWhere('p.durete IS NOT NULL')
            ->andWhere('p.typePh IS NOT NULL')
            ->orderBy('p.datePrelevement', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function nbrPrelevement(Utilisateur $utilisateur): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.preleveur = :user OR p.analyseur = :user')
            ->setParameter('user', $utilisateur)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function prelevementOrdreDate(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.datePrelevement', 'DESC')
            ->getQuery()
            ->getResult();
    }

    
}
