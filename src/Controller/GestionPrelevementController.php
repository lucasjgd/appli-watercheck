<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Emplacement;
use App\Entity\Preleveur;
use App\Entity\Prelevement;
use App\Entity\Analyseur;
use App\Entity\Utilisateur;
use App\Entity\Admin;
use App\Entity\TypePH;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

final class GestionPrelevementController extends AbstractController
{
    #[Route('/gestion_prelevement', name: 'gestion_prelevement')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $pagination, Request $request): Response
    {
        $utilisateur = $request->getSession()->get('utilisateur');
        if (!$utilisateur || strtolower($utilisateur['role']) !== 'admin' && strtolower($utilisateur['role']) !== 'preleveur' && strtolower($utilisateur['role']) !== 'analyseur') {
            return $this->redirectToRoute('index');
        }

        $emplacements = $entityManager->getRepository(Emplacement::class)->findAll();
        $prelevements = $entityManager->getRepository(Prelevement::class)->prelevementOrdreDate();
        $prelevementsPagination = $pagination->paginate(
            $prelevements,
            $request->query->getInt('page', 1),
            15
        );
        $typesPH = $entityManager->getRepository(TypePH::class)->findAll();

        return $this->render('gestion_prelevement.html.twig', [
            'emplacements' => $emplacements,
            'prelevements' => $prelevementsPagination,
            'types' => $typesPH,
        ]);
    }

    #[Route('/gestion_prelevement/ajout', name: 'gestion_prelevement_ajout', methods: ['POST'])]
    public function ajoutPrelevement(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logs): Response
    {
        
        $emplacementPrelevement = $request->request->get('emplacementPrelevement');
        $preleveur = $request->request->get('preleveurPrevelement');
        $verifRole = $entityManager->getRepository(Utilisateur::class)->find($preleveur);
        $emplacement = $entityManager->getRepository(Emplacement::class)->find($emplacementPrelevement);
        $prelevementAvecEmplacementExistant = $entityManager->getRepository(Prelevement::class)->findOneBy(['emplacement' => $emplacement]);


        if ($prelevementAvecEmplacementExistant) {
            $this->addFlash('error', "Prélèvement déjà présent avec cet emplacement.");
            return $this->redirectToRoute('gestion_prelevement');
        }

        $date = new \DateTimeImmutable('today');

        $prelevement = new Prelevement();
        $prelevement->setEmplacement($emplacement);
        $prelevement->setDatePrelevement($date);


        if ($verifRole instanceof Preleveur or $verifRole instanceof Admin) {
            $prelevement->setPreleveur($verifRole);
        } else {
            $this->addFlash('error', "Vous n'êtes pas un preleveur.");
            return $this->redirectToRoute('gestion_prelevement');
        }

        $entityManager->persist($prelevement);
        $entityManager->flush();

        $logs->info('Prélèvement ajouté', [
            'date' => $date->format('Y-m-d'),
            'preleveur_id' => $verifRole->getId(),
        ]);

        $this->addFlash('success', "Prélèvement ajouté avec succés.");
        return $this->redirectToRoute('gestion_prelevement');
    }

    #[Route('/gestion_prelevement/analyse/{id}', name: 'gestion_prelevement_analyse', methods: ['POST'])]
    public function analysePrelevement(int $id, Request $request, EntityManagerInterface $entityManager, LoggerInterface $logs): Response
    {
        $prelevement = $entityManager->getRepository(Prelevement::class)->find($id);
        if (!$prelevement) {
            $this->addFlash('error', "Prélèvement non trouvé.");
            return $this->redirectToRoute('gestion_prelevement');
        }

        $analyseur = $request->request->get('analyseurPrevelement');
        $verifRole = $entityManager->getRepository(Utilisateur::class)->find($analyseur);

        if ($verifRole instanceof Analyseur or $verifRole instanceof Admin) {
            $prelevement->setAnalyseur($verifRole);
        } else {
            $this->addFlash('error', "Vous n'êtes pas un analyseur.");
            return $this->redirectToRoute('gestion_prelevement');
        }

        $conductivite = $request->request->get('conductivite');
        $turbidite = $request->request->get('turbidite');
        $durete = $request->request->get('durete');
        $alcalinite = $request->request->get('alcalinite');
        $typePh = $request->request->get('typePh');

        $verifType = $entityManager->getRepository(TypePH::class)->find($typePh);

        $prelevement->setConductivite($conductivite);
        $prelevement->setTurbidite($turbidite);
        $prelevement->setDurete($durete);
        $prelevement->setAlcalinite($alcalinite);
        $prelevement->setTypePh($verifType);

        $entityManager->flush();

        $logs->info('Prélèvement analysé', [
            'prelevement_id' => $prelevement->getId(),
            'analyseur_id' => $verifRole->getId(),
        ]);

        $this->addFlash('success', "Prélèvement analysé avec succés.");
        return $this->redirectToRoute('gestion_prelevement');
    }

    #[Route('/gestion_prelevement/modif/{id}', name: 'gestion_prelevement_modif', methods: ['POST'])]
    public function modifPrelevement(int $id, Request $request, EntityManagerInterface $entityManager, SessionInterface $session, ): Response
    {
        $prelevement = $entityManager->getRepository(Prelevement::class)->find($id);

        if (!$prelevement) {
            $this->addFlash('error', 'Prélèvement introuvable.');
            return $this->redirectToRoute('gestion_prelevement');
        }

        $utilisateur = $session->get('utilisateur');
        $role = strtolower($utilisateur['role']);

        if (in_array($role, ['preleveur', 'admin'])) {
            $datePrelevement = $request->request->get('modifDatePrelevement');
            $emplacementId = $request->request->get('modifEmplacement');

            if ($datePrelevement) {
                $prelevement->setDatePrelevement(new \DateTime($datePrelevement));
            }

            if ($emplacementId) {
                $emplacement = $entityManager->getRepository(Emplacement::class)->find($emplacementId);
                if ($emplacement) {
                    $prelevement->setEmplacement($emplacement);
                }
            }
        }

        if (in_array($role, ['analyseur', 'admin'])) {
            $conductivite = $request->request->get('modifConductivite');
            $turbidite = $request->request->get('modifTurbidite');
            $alcalinite = $request->request->get('modifAlcalinite');
            $durete = $request->request->get('modifDurete');
            $typePhId = $request->request->get('modifTypePh');

            if ($conductivite !== null)
                $prelevement->setConductivite(floatval($conductivite));
            if ($turbidite !== null)
                $prelevement->setTurbidite(floatval($turbidite));
            if ($alcalinite !== null)
                $prelevement->setAlcalinite(floatval($alcalinite));
            if ($durete !== null)
                $prelevement->setDurete(floatval($durete));

            if ($typePhId) {
                $typePh = $entityManager->getRepository(className: TypePH::class)->find($typePhId);
                if ($typePh) {
                    $prelevement->setTypePh($typePh);
                }
            }
        }

        $entityManager->flush();
        $this->addFlash('success', "Prélèvement modifié avec succés.");
        return $this->redirectToRoute('gestion_prelevement');
    }

    #[Route('/gestion_prelevement/modif-nonAnalyse/{id}', name: 'gestion_prelevement_modif_nonAnalyse', methods: ['POST'])]
    public function modifPrelevementNonAnalyse(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $prelevement = $entityManager->getRepository(Prelevement::class)->find($id);

        if (!$prelevement) {
            $this->addFlash('error', 'Prélèvement introuvable.');
            return $this->redirectToRoute('gestion_prelevement');
        }

        $datePrelevement = $request->request->get('modifDatePrelevementNonAnalyse');
        $emplacementId = $request->request->get('modifEmplacementPrelevementNonAnalyse');

        if ($datePrelevement) {
            $prelevement->setDatePrelevement(new \DateTime($datePrelevement));
        }

        if ($emplacementId) {
            $emplacement = $entityManager->getRepository(Emplacement::class)->find($emplacementId);
            if ($emplacement) {
                $prelevement->setEmplacement($emplacement);
            }
        }

        $entityManager->flush();

        $this->addFlash('success', "Prélèvement modifié avec succés.");
        return $this->redirectToRoute('gestion_prelevement');
    }

    #[Route('/gestion_prelevement/suppr/{id}', name: 'gestion_prelevement_suppr', methods: ['POST'])]
    public function supprPrelevement(int $id, EntityManagerInterface $entityManager, LoggerInterface $logs): Response
    {
        $prelevement = $entityManager->getRepository(Prelevement::class)->find($id);
        if (!$prelevement) {
            $this->addFlash('error', "Prélèvement non trouvé.");
        }

        $logs->info('Prélèvement supprimé', [
            'prelevement_id' => $prelevement->getId(),
        ]);
        $entityManager->remove($prelevement);
        $entityManager->flush();

        $this->addFlash('success', "Prélèvement supprimé avec succés.");
        return $this->redirectToRoute('gestion_prelevement');
    }

}
