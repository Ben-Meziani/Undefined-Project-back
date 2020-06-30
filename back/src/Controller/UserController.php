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
use Intervention\Image\ImageManagerStatic as Image;


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
        
        $token = $request->cookies->get('BEARER');
        //dd($token);
        //$token_trim = explode(';', $token)[1];
        //$token_trim = explode('=', $token_trim)[1];
        //dd($token_trim, $token);
        $token_decoded = $jwtEncoder->decode($token);
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

        $json = $request->request;
        dd($json);
        if ($request->isMethod('GET')) {
            //recup user et renvoie
            return $this->json($user, 200);
        } else {
            //patch les donée
            $icon = $request->files->get('icon'); //OK
            $error = $validator->validate($user);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }
            //dd($icon);
            $this->uploadImageRoom($icon, $id);
            $serializer->deserialize($json, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
            $user->setUpdatedAt(new DateTime());

            $this->getDoctrine()->getManager()->flush();
            return $this->json(200);
        }
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete($id, Request $request, JWTEncoderInterface $jwtEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$this->checkToken($jwtEncoder, $request, $user)) {
            return $this->json('invalid token', 403);
        }

        if ($user) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($user);
            $manager->flush();
            return $this->json(200);
        }
        return $this->json(404);
    }

    /**
     * @Route("/{id}/icon", name="user_icon", methods={"POST", "GET"})
     */
    public function uploadImageRoom($file, $user)
    {
        //if ($this->checkToken($jwtEncoder, $request, $user)) {
            //if ($request->isMethod('POST')) {
                //dd($request)
                $userEntity = $this->getDoctrine()->getRepository(User::class)->find($user);
                
                if ($file) {
                    $fileName = uniqid() . '.' . $file->guessExtension();
                    

                    $file->move($this->getParameter('icon_directory'), $fileName);
                    //dd($this->getParameter('icon_directory'));
                    $file = Image::make($this->getParameter('icon_directory').'/'.$fileName)
                        ->resize(400, null, function ($constraint) 
                            {
                                $constraint->aspectRatio();
                            })
                        ->save();
                    $userEntity->setIcon($fileName);
                }
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                
                
            //} else {
                //part where we send the picture
            //    $image = Image::make($this->getParameter('icon_directory').'/'.$user->getIcon());
            //    return $image->response();
            //}
        //}
        //else {
        //    return $this->json('invalid token', 403);
        //}
    }
}
