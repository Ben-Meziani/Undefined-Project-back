<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
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
    public function register(Request $request, SerializerInterface $serializer, EntityManager $em)
    {
        $jsonRecu = $request->getContent();
        
        $post = $serializer->deserialize($jsonRecu, User::class, 'json');
        
        //$mdp = $post->getPassword();
        //on encrypte le mdp
        //$post->setPassword(le mdp encrypter)

        //persist et flush  
       // $em->persist($post);
       // $em->flush();
    }
}
