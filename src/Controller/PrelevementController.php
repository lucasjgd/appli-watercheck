<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prelevement;
use App\Entity\Ville;
use App\Entity\Region;



final class PrelevementController extends AbstractController
{
    #[Route('/prelevements', name: 'prelevements')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $prelevements = $entityManager->getRepository(Prelevement::class)->estAnalyse();
        $villes = $entityManager->getRepository(Ville::class)->findAll();
        $regions = $entityManager->getRepository(Region::class)->findAll();
        return $this->render('prelevements.html.twig', ['prelevements' => $prelevements,'regions' => $regions,'villes' => $villes]);
    }
}
