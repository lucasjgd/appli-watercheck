<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypePH;

final class GestionTypePHController extends AbstractController
{
    #[Route('gestion_type_ph', name: 'gestion_type_ph')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $types = $entityManager->getRepository(TypePH::class)->findAll();

        return $this->render('gestion_type_ph.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/gestion_type_ph/ajout', name: 'gestion_type_ph_ajout', methods: ['POST'])]
    public function ajoutType(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nomType = $request->request->get('nomType');

        $type = new TypePH();
        $type->setLibelle($nomType);

        $entityManager->persist($type);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_type_ph');
    }

    #[Route('/gestion_type_ph/modif/{id}', name: 'gestion_type_ph_modif', methods: ['POST'])]
    public function modifType(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = $entityManager->getRepository(TypePH::class)->find($id);
        if (!$type) {
            throw $this->createNotFoundException("Type non trouvé.");
        }

        $nomType = $request->request->get('nomType');

        $type->setLibelle($nomType);

        $entityManager->flush();

        return $this->redirectToRoute('gestion_type_ph');
    }

    #[Route('/gestion_type_ph/suppr/{id}', name: 'gestion_type_ph_suppr', methods: ['POST'])]
    public function supprEmplacement(int $id, EntityManagerInterface $entityManager): Response
    {
        $type = $entityManager->getRepository(TypePH::class)->find($id);
        if (!$type) {
            throw $this->createNotFoundException("Type non trouvé.");
        }

        $entityManager->remove($type);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_type_ph');
    }
}
