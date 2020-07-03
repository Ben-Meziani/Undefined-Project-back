<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {



        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        //dd(empty($error));
        if (empty($error)) {
           return $this->json(200);
           
        } else {
            return $this->json($error, 401);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/logout/confirm", name="app_logout_confirm")
     */
    public function confirmLogout()
    {
        return $this->json(200);
    }

    /**
     * @Route("/forgotten-pass", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request,UserRepository $user, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);
        //decode json
        $data = json_decode($request->getContent());
        //dd($data->email);
        // On traite le formulaire
        //$form->handleRequest($data->email);

        // Si le formulaire est valide
        
        // On récupère les données
        //$donnees = $form->getData();

        // On cherche un utilisateur ayant cet e-mail
        $user = $user->findOneByEmail($data->email);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On envoie une alerte disant que l'adresse e-mail est inconnue
            $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            
            // On retourne sur la page de connexion
            return $this->json('email inconnue', 401);
        }

        // On génère un token
        $token = $tokenGenerator->generateToken();

        // On essaie d'écrire le token en base de données
        try {
            $user->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            
            return $this->json('erreur', 500);
        }

        // On génère l'URL de réinitialisation de mot de passe
        $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        // On génère l'e-mail
        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('no-reply@monadresse.fr')
            ->setTo($user->getEmail())
            ->setBody(
                "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site undefined-project.tk/. Veuillez cliquer sur le lien suivant : " . $url,
                'text/html'
            )
        ;

        // On envoie l'e-mail
        $mailer->send($message);


        // On redirige vers la page de login
        return $this->json(200);
        
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
 // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);
        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire est envoyé en méthode post
        //if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            //$user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // A password reset token should be used only once, remove it.
    
                // Encode the plain password, and set it.
                $encodedPassword = $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                );

                $user->setPassword($encodedPassword);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                //dump($form->get('plainPassword')->getData());
                //dd($user);
    
             
                //return $this->redirect('http://adressedusite.com');
                return $this->redirectToRoute('homepage');
            } else{
            //dd('coucou');
                $this->addFlash('reset_password_error',
                    'There was a problem validating your reset request - %s'
                );
            }
/* 
            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
 */

            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['passwordForm' => $form->createView(), 'token' => $token]);
        

    }

}
