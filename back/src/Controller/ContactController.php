<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        yooooooooooooooooooooooooooooo
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            //  Ici on envoie l'email  
            $message = (new \Swift_Message('Nouveau Contact'))

                // On attribue l'expéditeur
                ->setFrom($contact['email'])

                //On attribue le destinataire
                ->setTo('toufik-m@live.fr')

                //On créé le message avec view twig 
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        compact('contact')
                    ),
                    'text/html'
                );
                //On envoie le message 
                $mailer->send($message);
                $this->addFlash('message', 'Le message à bien été envoyé');
                return $this->redirectToRoute('homepage');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
