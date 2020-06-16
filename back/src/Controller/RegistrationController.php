<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_registration", methods={"GET"})
     */
    public function register(Request $request, SerializerInterface $serializer)
    {
        $jsonRecu = $request->getContent();
    }
}
