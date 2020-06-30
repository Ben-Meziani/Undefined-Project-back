<?php

namespace App\Controller;

use App\Entity\Pseudo;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PseudoController extends AbstractController
{
    /**
     * @Route("/pseudo", name="pseudo")
     */
    public function index()
    {
        return $this->render('pseudo/index.html.twig', [
            'controller_name' => 'PseudoController',
        ]);
    }

     /**
     * @Route("/add", name="pseudo_add", methods={"POST"})
     */
    public function addPseudo(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $pseudo = new Pseudo;
        $json = $request->getContent();
        
        $pseudo = $serializer->deserialize($json, Pseudo::class, 'json');
        $error = $validator->validate($pseudo);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $pseudo->setCreatedAt(new DateTime());
        $em->persist($pseudo);
        $em->flush();
        // ...


        return $this->json(200);
    }
/**
     *  @Route("/{id}/edit", name="pseudo_edit", methods={"GET|POST"})
     */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $pseudo = $this->getDoctrine()->getRepository(Pseudo::class)->find($id);
        $json = $request->getContent();
        
        if (!$json) {
             //recup pseudo et renvoie
             
            return $this->json($pseudo, 200);
        } else 
        {
            //patch les données
            $error = $validator->validate($pseudo);//<--- ici j'apelle validate avec $pseudo qui est null mais qui ne plantera pas car $pseudo existe
            if (count($error) > 0) {
                return $this->json($error, 400);
            }

            $serializer->deserialize($json, Pseudo::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $pseudo]);
            $pseudo->setUpdatedAt(new DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->json(200);
        }
    }

/**
     * @Route("/{id}/delete", name="delete_pseudo", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete($id, Request $request)
    {
        $pseudo = $this->getDoctrine()->getRepository(Pseudo::class)->find($id);

        if ($pseudo) {
            $this->isCsrfTokenValid('delete' . $pseudo->getId(), $request->request->get('_token'));
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($pseudo);
            $manager->flush();
            return $this->json(200);
        }
        return $this->json(404);
    }
}
