<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, Request $request, JWTEncoderInterface $jwtEncoder)
    {
        $token = $request->headers->get('authorization');
        
        $token_decoded = $jwtEncoder->decode($token);
        
        if ($user->getEmail() == $token_decoded["username"]) {
            return $this->json($user, 200);
        }
        else {
            return $this->json(403);
        }
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
            //dd($user);
            $serializer->deserialize($json, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
            $user->setUpdatedAt(new DateTime());

            $this->getDoctrine()->getManager()->flush();
            return $this->json(200);
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
