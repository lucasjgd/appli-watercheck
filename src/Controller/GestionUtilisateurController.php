<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionUtilisateurController extends AbstractController
{
    #[Route('/gestion_utilisateur', name: 'gestion_utilisateur')]
    public function index(): Response
    {
        return $this->render('gestion_utilisateur.html.twig', [
            'controller_name' => 'GestionUtilisateurController',
        ]);
    }
}
