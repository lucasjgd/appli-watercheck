<?php 
namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class InscriptionController extends AbstractController
{
    #[Route('/traitement-inscription', name: 'traitement-inscription')]
    public function traitementInscription(Request $request, EntityManagerInterface $em): Response
    {
        $utilisateur = new Client();

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('registerName');
            $mail = $request->request->get('registerEmail');
            $mdp = $request->request->get('registerPassword');
            $confirmMdp = $request->request->get('registerPasswordConfirmation');

            if ($mdp !== $confirmMdp) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('connexion');
            }

            $verif = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/';
            if (!preg_match($verif, $mdp)) {
                $this->addFlash('error', 'Le mot de passe doit contenir au minimum 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
                return $this->redirectToRoute('connexion');
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
