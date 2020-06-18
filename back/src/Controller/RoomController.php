<?php

namespace App\Controller;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/room", name="room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/add", name="room_add", methods={"POST"})
     */
    public function addRoom(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $room = new Room;
        $json = $request->getContent();

        $room = $serializer->deserialize($json, Room::class, 'json');
        $error = $validator->validate($room);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $em->persist($room);
        $em->flush();
        // ...


        return $this->json(200);
    }

    /**
       *  @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
       */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $json = $request->getContent();
        if (!$json) {
            //recup room et renvoie
            $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
            return $this->json($room, 200);
        } else {
            //patch les données
        }


        $error = $validator->validate($room);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $this->getDoctrine()->getManager()->flush();
        $em->persist($room);
    }
    /**
      * @Route("/{id}/delete", name="delete_room", requirements={"id" = "\d+"}, methods={"DELETE"})
      */
    public function delete($id, Request $request)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if ($room) {
            $this->isCsrfTokenValid('delete' . $room->getId(), $request->request->get('_token'));
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($room);
            $manager->flush();
            return $this->json(200);
        }
        return $this->json(404);
    }
}