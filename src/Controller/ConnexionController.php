<?php

// src/Controller/ConnexionController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function index(): Response
    {
        return $this->render('connexion.html.twig');
    }


    #[Route('/traitement-connexion', name: 'traitement_connexion', methods: ['POST'])]
    public function traitementConnexion(Request $request, EntityManagerInterface $em, SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        $email = $request->request->get('email');
        $mdp = $request->request->get('password');

        $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $email]);

        if (!$utilisateur) {
            $this->addFlash('danger', 'Mail invalide.');
            return $this->redirectToRoute('connexion');
        }
        

        if (!$passwordHasher->isPasswordValid($utilisateur, $mdp)) {
            $this->addFlash('danger', 'Mot de passe invalide');
            return $this->redirectToRoute('connexion');
        }
        


        $session->set('utilisateur', [
            'id' => $utilisateur->getId(),
            'nom' => $utilisateur->getNom(),
            'mail' => $utilisateur->getMail(),
            'role' => (new \ReflectionClass($utilisateur))->getShortName(),
        ]);

        return $this->redirectToRoute('index');
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('connexion');
    }
}
