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
     * check if the user can acces data
     *
     * @return boolean
     */
    private function checkToken(JWTEncoderInterface $jwtEncoder, $request, $user)
    {
        $token = $request->headers->get('authorization');
        $token_trim = explode(' ', $token)[1];
        //dd($token_trim, $token);
        $token_decoded = $jwtEncoder->decode($token_trim);
        if ($user->getEmail() == $token_decoded["username"]) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, Request $request, JWTEncoderInterface $jwtEncoder)
    {   
        //dd($this->checkToken($jwtEncoder, $request, $user));
        if ($this->checkToken($jwtEncoder, $request, $user)) {
            return $this->json($user, 200);
        }
        else {
            return $this->json('token invalid', 403);
        }
    }
   
    /**
     *  @Route("/{id}/edit", name="user_edit", methods={"GET|POST"})
     */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, JWTEncoderInterface $jwtEncoder)
    {
        
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$this->checkToken($jwtEncoder, $request, $user)) {
            return $this->json('invalid token', 403);
        }

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
