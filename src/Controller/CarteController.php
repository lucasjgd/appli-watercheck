<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prelevement;
use App\Entity\Emplacement;
use App\Entity\Ville;
use App\Entity\Region;
use App\Repository\PrelevementRepository;

final class CarteController extends AbstractController
{
    #[Route('/carte', name: 'carte')]
public function index(EntityManagerInterface $entityManager, PrelevementRepository $prelevementRepo): Response
{
    $lesPrelevements = $entityManager->getRepository(Prelevement::class)->estAnalyse();

    $prelevements = [];

    foreach ($lesPrelevements as $prelevement) {
        $emplacement = $prelevement->getEmplacement();
        $ville = $emplacement->getVille();

        $etat = $prelevementRepo->evaluerQualite($prelevement);

        $prelevements[] = [
            'lieu' => $emplacement->getLibelle(),
            'ville' => $ville->getLibelle(),
            'coord' => [$emplacement->getLatitude(), $emplacement->getLongitude()],
            'date' => $prelevement->getDatePrelevement()->format('d/m/Y'),
            'etat' => $etat
        ];
    }

    return $this->render('carte.html.twig', [
        'prelevements' => $prelevements
    ]);
}

}
