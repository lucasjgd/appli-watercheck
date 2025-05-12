<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypePH;
use App\Entity\Prelevement;
use Knp\Component\Pager\PaginatorInterface;

final class GestionTypePHController extends AbstractController
{
    #[Route('gestion_type_ph', name: 'gestion_type_ph')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $pagination, Request $request): Response
    {
        $types = $entityManager->getRepository(TypePH::class)->findAll();

        $typesPagination = $pagination->paginate(
        $types,
        $request->query->getInt('page', 1), 
        5 
    );

        return $this->render('gestion_type_ph.html.twig', [
            'types' => $typesPagination,
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

        $this->addFlash('success', "Type pH ajouté avec succés.");
        return $this->redirectToRoute('gestion_type_ph');
    }

    #[Route('/gestion_type_ph/modif/{id}', name: 'gestion_type_ph_modif', methods: ['POST'])]
    public function modifType(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = $entityManager->getRepository(TypePH::class)->find($id);
        if (!$type) {
            $this->addFlash('error', "Type non trouvé.");
        }

        $nomType = $request->request->get('nomType');

        $type->setLibelle($nomType);

        $entityManager->flush();

        $this->addFlash('success', "Type modifié avec succés.");
        return $this->redirectToRoute('gestion_type_ph');
    }

    #[Route('/gestion_type_ph/suppr/{id}', name: 'gestion_type_ph_suppr', methods: ['POST'])]
    public function supprType(int $id, EntityManagerInterface $entityManager): Response
    {
        $type = $entityManager->getRepository(TypePH::class)->find($id);
        if (!$type) {
            $this->addFlash('error', "Type non trouvé.");
        }

        $prelevements = $entityManager->getRepository(Prelevement::class)->findBy(['typePh' => $type]);

        if (count($prelevements) > 0) {
            $this->addFlash('error', "Impossible de supprimer ce type car des prélèvements y sont liés.");
            return $this->redirectToRoute('gestion_type_ph');
        }

        $entityManager->remove($type);
        $entityManager->flush();

        $this->addFlash('success', "Type supprimé avec succés.");
        return $this->redirectToRoute('gestion_type_ph');
    }
}
