<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_registration", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer)
    {
        $jsonRecu = $request->getContent();
        
        $post = $serializer->deserialize($jsonRecu, User::class, 'json');
        dd($post);
    }
}
