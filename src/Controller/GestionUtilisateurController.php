<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Entity\Admin;
use App\Entity\Analyseur;
use App\Entity\Preleveur;
use App\Entity\Client;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class GestionUtilisateurController extends AbstractController
{
    #[Route('/gestion_utilisateur', name: 'gestion_utilisateur')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findAll();

        return $this->render('gestion_utilisateur.html.twig', [
            'utilisateurs' => $utilisateur,
        ]);
    }

    #[Route('/gestion_utilisateur/ajout', name: 'gestion_utilisateur_ajout', methods: ['POST'])]
    public function ajoutUtilisateur(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hash): Response
    {
        $nom = $request->request->get('nom');
        $mdp = $request->request->get('mdp');
        $mail = $request->request->get('mail');
        $role = $request->request->get('role');

        $verif = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/';

        if (!preg_match($verif, $mdp)) {
            $this->addFlash('error', "Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
            return $this->redirectToRoute('gestion_utilisateur'); 
        }

        if ($role === "admin") {
            $utilisateur = new Admin();
        } else if ($role === "analyseur") {
            $utilisateur = new Analyseur();
        } else if ($role === "preleveur") {
            $utilisateur = new Preleveur();
        } else {
            $utilisateur = new Client();
        }

        $utilisateur->setNom($nom);
        $utilisateur->setMail($mail);
        $mdpHash = $hash->hashPassword($utilisateur, $mdp);
        $utilisateur->setMdp($mdpHash);

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_utilisateur');
    }

    #[Route('/gestion_utilisateur/modif/{id}', name: 'gestion_utilisateur_modif', methods: ['POST'])]
    public function modifUtilisateur(int $id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hash): Response
    {
        $utilisateur = $entityManager->getRepository(className: Utilisateur::class)->find($id);
        if (!$utilisateur) {
            throw $this->createNotFoundException("Utilisateur non trouvé.");
        }

        $ancienMdp = $utilisateur->getMdp();

        $verif = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/';

        $nom = $request->request->get('modif-nom');
        $mail = $request->request->get('modif-mail');
        $mdp = $request->request->get('modif-mdp');
        $role = $request->request->get('modif-role');

        if (!preg_match($verif, $mdp)) {
            $this->addFlash('error', "Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
            return $this->redirectToRoute('gestion_utilisateur'); 
        }

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        if ($role === "admin") {
            $nouvUtilisateur = new Admin();
        } else if ($role === "analyseur") {
            $nouvUtilisateur = new Analyseur();
        } else if ($role === "preleveur") {
            $nouvUtilisateur = new Preleveur();
        } else {
            $nouvUtilisateur = new Client();
        }

        $nouvUtilisateur->setNom($nom);
        $nouvUtilisateur->setMail($mail);


        if ($mdp) {
            $mdpHash = $hash->hashPassword($nouvUtilisateur, $mdp);
            $nouvUtilisateur->setMdp($mdpHash);
        } else {
            $nouvUtilisateur->setMdp($ancienMdp);
        }


        $entityManager->persist($nouvUtilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_utilisateur');
    }


    #[Route('/gestion_utilisateur/suppr/{id}', name: 'gestion_utilisateur_suppr', methods: ['POST'])]
    public function supprUtilisateur(int $id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);
        if (!$utilisateur) {
            throw $this->createNotFoundException("Utilisateur non trouvé.");
        }

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_utilisateur');
    }
}
