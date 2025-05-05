<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Emplacement;
use App\Entity\Ville;

final class GestionEmplacementController extends AbstractController
{
    #[Route('/gestion_emplacement', name: 'gestion_emplacement')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $emplacements = $entityManager->getRepository(Emplacement::class)->findAll();
        $villes = $entityManager->getRepository(Ville::class)->findAll();

        return $this->render('gestion_emplacement.html.twig', [
            'emplacements' => $emplacements,
            'villes' => $villes,
        ]);
    }


    #[Route('/gestion_emplacement/ajout', name: 'gestion_emplacement_ajout', methods: ['POST'])]
    public function addEmplacement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nomLieu = $request->request->get('nomLieu');
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $villeId = $request->request->get('villeLieu');

        $ville = $entityManager->getRepository(Ville::class)->find($villeId);
        if (!$ville) {
            throw $this->createNotFoundException("Ville non trouvée.");
        }

        $emplacement = new Emplacement();
        $emplacement->setLibelle($nomLieu);
        $emplacement->setLatitude((float) $latitude);
        $emplacement->setLongitude((float) $longitude);
        $emplacement->setVille($ville);

        $entityManager->persist($emplacement);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_emplacement');
    }


    #[Route('/gestion_emplacement/modif/{id}', name: 'gestion_emplacement_modif', methods: ['POST'])]
    public function modifEmplacement(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplacement = $entityManager->getRepository(Emplacement::class)->find($id);
        if (!$emplacement) {
            throw $this->createNotFoundException("Emplacement non trouvé.");
        }

        $nomLieu = $request->request->get('nomLieu');
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $villeId = $request->request->get('villeLieu');

        $ville = $entityManager->getRepository(Ville::class)->find($villeId);
        if (!$ville) {
            throw $this->createNotFoundException("Ville non trouvée.");
        }

        $emplacement->setLibelle($nomLieu);
        $emplacement->setLatitude((float) $latitude);
        $emplacement->setLongitude((float) $longitude);
        $emplacement->setVille($ville);

        $entityManager->flush();

        return $this->redirectToRoute('gestion_emplacement');
    }

    #[Route('/gestion_emplacement/suppr/{id}', name: 'gestion_emplacement_suppr', methods: ['POST'])]
    public function supprEmplacement(int $id, EntityManagerInterface $entityManager): Response
    {
        $emplacement = $entityManager->getRepository(Emplacement::class)->find($id);
        if (!$emplacement) {
            throw $this->createNotFoundException("Emplacement non trouvé.");
        }

        $entityManager->remove($emplacement);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_emplacement');
    }

}
