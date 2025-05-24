<?php

namespace App\Repository;

use App\Entity\Prelevement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;
use App\Entity\Emplacement;

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
        return $this->createQueryBuilder('p')
            ->orderBy('p.datePrelevement', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function filtrePrelevement(?string $ville, ?string $region): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.emplacement', 'e')
            ->join('e.ville', 'v')
            ->join('v.region', 'r')
            ->where('p.turbidite IS NOT NULL')
            ->andWhere('p.durete IS NOT NULL')
            ->andWhere('p.alcalinite IS NOT NULL')
            ->andWhere('p.typePh IS NOT NULL')
            ->andWhere('p.conductivite IS NOT NULL');


        if ($ville) {
            $qb->andWhere('v.libelle = :ville')->setParameter('ville', $ville);
        }

        if ($region) {
            $qb->andWhere('r.libelle = :region')->setParameter('region', $region);
        }

        return $qb->getQuery()->getResult();
    }

    public function evaluerQualite(Prelevement $p): string
    {
        $bonneConductivite = $p->getConductivite() < 1000;
        $bonneTurbidite = $p->getTurbidite() < 5;
        $bonneAlcalinite = $p->getAlcalinite() >= 5 && $p->getAlcalinite() <= 30;
        $bonneDurete = $p->getDurete() >= 10 && $p->getDurete() <= 25;
        $typePh = $p->getTypePh()?->getLibelle() ?? '';
        $typePh = strtolower($typePh);
        $typePh = str_replace('typeph ', '', $typePh);

        $bonPh = $typePh === 'ph neutre' || $typePh === 'ph basique';

        $criteresOk = 0;
        if ($bonneConductivite)
            $criteresOk++;
        if ($bonneTurbidite)
            $criteresOk++;
        if ($bonneAlcalinite)
            $criteresOk++;
        if ($bonneDurete)
            $criteresOk++;
        if ($bonPh)
            $criteresOk++;

        if ($criteresOk === 5) {
            return 'potable';
        } elseif ($criteresOk >= 3) {
            return 'moyennement-potable';
        } else {
            return 'non-potable';
        }

    }


}
