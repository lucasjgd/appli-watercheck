<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionPrelevementController extends AbstractController
{
    #[Route('/gestion_prelevement', name: 'gestion_prelevement')]
    public function index(): Response
    {
        return $this->render('gestion_prelevement.html.twig', [
            'controller_name' => 'GestionPrelevementController',
        ]);
    }
}
