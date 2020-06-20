<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PseudoController extends AbstractController
{
    /**
     * @Route("/pseudo", name="pseudo")
     */
    public function index()
    {
        return $this->render('pseudo/index.html.twig', [
            'controller_name' => 'PseudoController',
        ]);
    }
}
