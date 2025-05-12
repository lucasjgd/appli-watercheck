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



final class PrelevementController extends AbstractController
{
    #[Route('/prelevements', name: 'prelevements')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $pagination, Request $request): Response
    {
        $prelevements = $entityManager->getRepository(Prelevement::class)->estAnalyse();
        $prelevementsPagination = $pagination->paginate(
        $prelevements,
        $request->query->getInt('page', 1), 
        9 
    );
        $villes = $entityManager->getRepository(Ville::class)->findAll();
        $regions = $entityManager->getRepository(Region::class)->regionOrdreA();
        return $this->render('prelevements.html.twig', ['prelevements' => $prelevementsPagination,'regions' => $regions,'villes' => $villes]);
    }
}
