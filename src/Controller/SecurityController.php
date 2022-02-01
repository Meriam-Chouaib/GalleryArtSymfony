<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('Home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="Registration")
     */

    public function registration (Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form= $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $hash= $encoder -> encodePassword($user,$user->getPassword()); //hachage de password
            $user->setPassword($hash); //enregistrement dans bd mdp crypté
            $em->persist($user);
            $em->flush();
          
            return $this->redirectToRoute('ListJob');
        }

        return $this->render('security/registration.html.twig',['form'=> $form->createView()]);

        
    }
}
