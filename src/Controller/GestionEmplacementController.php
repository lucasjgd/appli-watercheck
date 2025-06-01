<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Emplacement;
use App\Entity\Ville;
use App\Entity\Prelevement;
use Knp\Component\Pager\PaginatorInterface;


final class GestionEmplacementController extends AbstractController
{
    #[Route('/gestion_emplacement', name: 'gestion_emplacement')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $pagination, Request $request): Response
    {
        $utilisateur = $request->getSession()->get('utilisateur');
        if (!$utilisateur || strtolower($utilisateur['role']) !== 'admin' || strtolower($utilisateur['role']) !== 'preleveur') {
            return $this->redirectToRoute('index');
        }

        $emplacements = $entityManager->getRepository(Emplacement::class)->emplacementOrdreVille();

        $emplacementsPagination = $pagination->paginate(
            $emplacements,
            $request->query->getInt('page', 1),
            15
        );

        $villes = $entityManager->getRepository(Ville::class)->findAll();

        return $this->render('gestion_emplacement.html.twig', [
            'emplacements' => $emplacementsPagination,
            'villes' => $villes,
        ]);
    }


    #[Route('/gestion_emplacement/ajout', name: 'gestion_emplacement_ajout', methods: ['POST'])]
    public function ajoutEmplacement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nomLieu = $request->request->get('nomLieu');
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $villeId = $request->request->get('villeLieu');

        $ville = $entityManager->getRepository(Ville::class)->find($villeId);
        if (!$ville) {
            $this->addFlash('error', "Ville non trouvée.");
        }

        $emplacement = new Emplacement();
        $emplacement->setLibelle($nomLieu);
        $emplacement->setLatitude((float) $latitude);
        $emplacement->setLongitude((float) $longitude);
        $emplacement->setVille($ville);

        $entityManager->persist($emplacement);
        $entityManager->flush();

        $this->addFlash('success', "Emplacement ajouté avec succés.");
        return $this->redirectToRoute('gestion_emplacement');
    }


    #[Route('/gestion_emplacement/modif/{id}', name: 'gestion_emplacement_modif', methods: ['POST'])]
    public function modifEmplacement(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplacement = $entityManager->getRepository(Emplacement::class)->find($id);
        if (!$emplacement) {
            $this->addFlash("error", "Emplacement non trouvé.");
        }

        $nomLieu = $request->request->get('nomLieu');
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $villeId = $request->request->get('villeLieu');

        $ville = $entityManager->getRepository(Ville::class)->find($villeId);
        if (!$ville) {
            $this->addFlash('error', "Ville non trouvée.");
        }

        $emplacement->setLibelle($nomLieu);
        $emplacement->setLatitude((float) $latitude);
        $emplacement->setLongitude((float) $longitude);
        $emplacement->setVille($ville);

        $entityManager->flush();

        $this->addFlash('success', "Emplacement modifié avec succés.");
        return $this->redirectToRoute('gestion_emplacement');
    }

    #[Route('/gestion_emplacement/suppr/{id}', name: 'gestion_emplacement_suppr', methods: ['POST'])]
    public function supprEmplacement(int $id, EntityManagerInterface $entityManager): Response
    {
        $emplacement = $entityManager->getRepository(Emplacement::class)->find($id);
        if (!$emplacement) {
            $this->addFlash('error', "Emplacement non trouvé.");
        }

        $prelevements = $entityManager->getRepository(Prelevement::class)->findBy(['emplacement' => $emplacement]);

        if (count($prelevements) > 0) {
            $this->addFlash('error', "Impossible de supprimer cet emplacement car des prélèvements y sont liés.");
            return $this->redirectToRoute('gestion_emplacement');
        }


        $entityManager->remove($emplacement);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_emplacement');
    }

}
