<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PrelevementController extends AbstractController
{
    #[Route('/prelevements', name: 'prelevements')]
    public function index(): Response
    {
        return $this->render('prelevements.html.twig');
    }
}
