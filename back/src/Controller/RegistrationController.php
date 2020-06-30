<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTime;
use App\Service\MailerService;
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
     * @Route("/register", name="app_registration")
     */
    public function register(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, \Swift_Mailer  $mailer)
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
       
        //encode password
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        ));
        
        $error = $validator->validate($user);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        // On génère le token d'activation
         $user->setActivationToken(md5(uniqid()));

        $em->persist($user);
        $em->flush();

        // do anything else you need here, like send an email
        // on créé le message 
        $message =(new \Swift_Message('Activation de votre compte'))
        
        // On attribue l'expediteur 
        ->setFrom('noreply@server.com')
        
        //on attribue le destinataire 
        ->setTo($user->getEmail())

        //on créé le contenu
        ->setBody(
            $this->renderView(
                'emails/activation.html.twig', ['token' => $user->getActivationToken()]
            ),
            'text/html'
        )
;
        $mailer->send($message);
        return $this->json($user, 200);
    }

    /**
     *@Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $userRepo)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $userRepo->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 200);

     }
}
