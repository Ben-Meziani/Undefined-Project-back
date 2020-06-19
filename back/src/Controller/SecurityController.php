<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {


        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //dd($error);
        //if (count($error) > 0) {
            return $this->json(200);
        //}
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        
    }

    /**
     * @Route("/logout/confirm", name="app_logout_confirm")
     */
    public function confirmLogout()
    {
        return $this->json(200);
    }
}
