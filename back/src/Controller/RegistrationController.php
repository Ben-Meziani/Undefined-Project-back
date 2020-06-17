<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    private $passwordEncoder;

         public function __construct(UserPasswordEncoderInterface $passwordEncoder)
         {
             $this->passwordEncoder = $passwordEncoder;
         }
    

    /**
     * @Route("/register", name="app_registration", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $jsonRecu = $request->getContent();

        $post = $serializer->deserialize($jsonRecu, User::class, 'json');
        $user = new User();
        // ...

        $user->setPassword($this->passwordEncoder->encodePassword(
            $post,
            'password'
        ));
        
        //$mdp = $post->getPassword();
        //on encrypte le mdp
        //$post->setPassword(le mdp encrypter)

        //persist et flush  
        // $em->persist($post);
        // $em->flush();
    }
}
