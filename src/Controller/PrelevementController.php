<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prelevement;
use App\Entity\Ville;
use App\Entity\Region;
use App\Repository\PrelevementRepository;



final class PrelevementController extends AbstractController
{
    #[Route('/prelevements', name: 'prelevements')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $pagination, Request $request, PrelevementRepository $prelevementRepo ): Response
    {
        $ville = $request->query->get('ville');
        $region = $request->query->get('region');
        $qualite = $request->query->get('qualite');

        $prelevementsFiltres = $entityManager->getRepository(Prelevement::class)->filtrePrelevement($ville, $region);

        $prelevementsQualites = [];

        foreach ($prelevementsFiltres as $p) {
            $resultat = $prelevementRepo->evaluerQualite($p);
            $p->qualite = $resultat;

            if (!$qualite || $qualite === $resultat) {
                $prelevementsQualites[] = $p;
            }
        }


        $prelevementsPagination = $pagination->paginate(
            $prelevementsQualites,
            $request->query->getInt('page', 1),
            9
        );

        $villes = $entityManager->getRepository(Ville::class)->findAll();
        $regions = $entityManager->getRepository(Region::class)->regionOrdreA();

        return $this->render('prelevements.html.twig', [
            'prelevements' => $prelevementsPagination,
            'regions' => $regions,
            'villes' => $villes,
        ]);
    }

    
}
