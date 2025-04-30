<?php 
namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class InscriptionController extends AbstractController
{
    #[Route('/traitement-inscription', name: 'traitement-inscription')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $utilisateur = new Client();

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('registerName');
            $mail = $request->request->get('registerEmail');
            $mdp = $request->request->get('registerPassword');
            $confirmMdp = $request->request->get('registerPasswordConfirmation');

            if ($mdp !== $confirmMdp) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('inscription');
            }

            $utilisateur->setNom($nom)
                        ->setMail($mail)
                        ->setMdp(password_hash($mdp, PASSWORD_BCRYPT));

            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Inscription réussie!');
            return $this->redirectToRoute('connexion'); 
        }

        return $this->render('inscription/index.html.twig');
    }
}
