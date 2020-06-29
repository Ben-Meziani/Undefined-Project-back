<?php

namespace App\Controller;

use DateTime;
use App\Entity\Room;
use App\Entity\User;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Ramsey\Uuid\Uuid;


/**
 * @Route("/room", name="room")
 */
class RoomController extends AbstractController
{
 
    /**
     * @Route("/{id}/upload", name="room_upload", methods={"POST", "GET"})
     */
    public function uploadImageRoom(Request $request, Room $room)
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('post');

            if ($file) {
                $fileName = uniqid() . '.' . $file->guessExtension();

                $file->move($this->getParameter('file_directory'), $fileName);

                $room->setFiles($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->json($room->getFiles(), 200);
        }
        return $this->json($room, 200);
    }



    /**
     * @Route("/add", name="room_add", methods={"POST"})
     */
    public function addRoom(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $room = new Room;
        $json = $request->getContent();
        $room = $serializer->deserialize($json, Room::class, 'json');
        //decode to retreive game master id
        $gmId = get_object_vars(json_decode($json));
        $user = $this->getDoctrine()->getRepository(User::class)->find($gmId['gameMaster']);
        $room->setTheme('HP');
        $room->setGameMaster($user);
        $room->setRoomPassword(uniqid("pw"));
        //dd($room);
    
        $error = $validator->validate($room);
        if (count($error) > 0) {
            return $this->json($error, 400);
        }
        $room->setCreatedAt(new DateTime());
        $em->persist($room);
        $em->flush();
        
        //dd($room); 

        return $this->json(['uniqueId'=>$room->getUuid(), 'paswword'=>$room->getRoomPassword()], 200);
    }

    /**
     * @Route("/{id}/join", name="room_join", methods={"POST"})
     */
    public function joinRoom(Request $request, User $user, EntityManagerInterface $em)
    {
        $json = get_object_vars(json_decode($request->getContent()));
        if (!is_null($json)) {
            $idRoom = $json['uniqueId'];
            $password = $json['password'];
            //custom request at the table room
            $conn = $em->getConnection();
            $sql = '
                SELECT * FROM room r
                WHERE r.room_password = :password
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['password' => $password]);
            $room = $stmt->fetchAll();

            $roomEntity = $this->getDoctrine()->getRepository(Room::class)->find($room[0]['id']);
            $roomEntity->addPlayer($user);
            $em->flush();
        }
        dd($idRoom, $password, $user, $roomEntity);
    }


    /**
     *  @Route("/{id}/edit", name="room_edit", methods={"GET|POST"})
     */
    public function edit(Int $id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);
        $json = $request->getContent();

        if (!$json) {
            //recup room et renvoie

            return $this->json($room, 200);
        } else {
            //patch les données
            $error = $validator->validate($room); //<--- ici j'apelle validate avec $room qui est null mais qui ne plantera pas car $room existe
            if (count($error) > 0) {
                return $this->json($error, 400);
            }

            $serializer->deserialize($json, Room::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $room]);
            $room->setUpdatedAt(new DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->json(200);
        }
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
