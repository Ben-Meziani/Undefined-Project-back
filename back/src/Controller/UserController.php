<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            $user,
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
     *  @Route("/{id}/edit", name="user_edit", methods={"GET|POST"})
     */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $json = $request->getContent();
        if (!$json) {
            //recup user et renvoie
            return $this->json($user, 200);
        } else {
            //patch les donée

            $error = $validator->validate($user);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }

            $serializer->deserialize($json, Room::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
            $this->getDoctrine()->getManager()->flush();
            
        }
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user) {
            $this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'));
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($user);
            $manager->flush();
            return $this->json(200);
        }
        return $this->json(404);
    }
}
