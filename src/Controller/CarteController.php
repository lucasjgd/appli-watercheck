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

final class CarteController extends AbstractController
{
    #[Route('/carte', name: 'carte')]
public function index(EntityManagerInterface $entityManager): Response
{
    $prelevementsEntities = $entityManager->getRepository(Prelevement::class)->estAnalyse();

    $prelevements = [];

    foreach ($prelevementsEntities as $prelevement) {
        $emplacement = $prelevement->getEmplacement();
        $ville = $emplacement->getVille();

        $criteresOk = 0;
        if ($prelevement->getConductivite() < 1000) $criteresOk++;
        if ($prelevement->getTurbidite() < 5) $criteresOk++;
        if ($prelevement->getAlcalinite() >= 5 && $prelevement->getAlcalinite() <= 30) $criteresOk++;
        if ($prelevement->getDurete() >= 10 && $prelevement->getDurete() <= 25) $criteresOk++;
        $ph = strtolower( $prelevement->getTypePh()->getLibelle());
        if (in_array($ph, ['ph neutre', 'ph basique'])) $criteresOk++;

        if ($criteresOk == 5) $etat = 'Potable';
        elseif ($criteresOk >= 3) $etat = 'Moyennement potable';
        else $etat = 'Non potable';

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
