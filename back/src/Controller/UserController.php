<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/new", name="user_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $user = new User();
        $json = $request->getContent();

        $user = $serializer->deserialize($json, User::class, 'json');
       
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user->getPassword()
        ));
        

        $error = $validator->validate($user);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $em->persist($user);
        $em->flush();
        // ...

       
        return $this->json(200);

    }
    /**
     *  @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $json = $request->getContent();
        if (!$json) {
            //recup user et renvoie
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            return $this->json($user, 200);
          }
          
          else {
            //patch les donée
          }
        
        dd($user);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        ));
        

        $error = $validator->validate($user);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $this->getDoctrine()->getManager()->flush(); $em->persist($user);

    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        
        if (!$user) 
       {
         return $this->json(404);
         }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
       return $this->json(200);
    
    }
   

}
