<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Intervention\Image\ImageManagerStatic as Image;

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
    public function register(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $user = new User();
        $json = $request->request->all();
        if(!is_null($json)) {
            $json = ['json' => $request->getContent()];
            
        }
        $user = $serializer->deserialize($json['json'], User::class, 'json');
        //add icon file
        if(!is_null($request->files->get('icon'))) {
            $file = $request->files->get('icon');
            if ($file) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('icon_directory'), $fileName);
                $file = Image::make($this->getParameter('icon_directory').'/'.$fileName)
                    ->resize(400, null, function ($constraint) 
                        {
                            $constraint->aspectRatio();
                        })
                    ->save();
                $user->setIcon($fileName);
            }
        }

        $user->setCreatedAt(new DateTime());
        
        $errors = $validator->validate($user);
        
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $message[] = [$error->getMessage()];
            }
            return $this->json($message, 400);
        }
        //encode password
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        ));

        $em->persist($user);
        $em->flush();

        return $this->json($user, 200);
    }

    
}
