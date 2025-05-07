<?php

namespace App\Repository;

use App\Entity\Prelevement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

}
