<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;
        
        $form = $this->createForm(RegistrationType::class, $user);

        //dump($request);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            // On récupère le mot de passe du formulaire (non haché poour le moment) afin de le transmettre à la méthode encoderPassword() qui va se charger d'encoder / crypter / hacher le mot de passe  

            $user->setPassword($hash);// on envoie le mot de passe haché dans le setteur de l'objet $user afin qu'il soit inséré dans la BDD

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login'); // On redirige vers la page de connexion après inscription
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("deconnexion", name="security_logout")
     */
    public function logout()
    {
        // cette function ne retourne rien, il suffit d'avoir une route pour le deconnexion (voir security.yaml / firewalls)
    }

    /*
        security.yaml :

        providers : où ce trouve les données à controller
        fireWalls : quelles parties de site nous allons protéger et par quel moyen (formulaire de connexion) 
    */
}
