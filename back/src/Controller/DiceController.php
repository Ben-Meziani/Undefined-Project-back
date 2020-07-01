<?php

namespace App\Controller;

use App\Entity\Dice;
use App\Entity\Room;
use App\Entity\User;
use DateTime;
use PhpParser\ErrorHandler\Throwing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dice")
 */
class DiceController extends AbstractController
{
    /**
     * @Route("/{id}", name="dice_roll")
     */
    public function roll(User $user, Request $request)
    {
        $json = $request->getContent();
        $params = json_decode($json);
        $resultat = $this->apiDice($params->dice, $params->launch);
        $this->save($user, $resultat, $params->dice, $params->room);

        return $this->json($resultat, 200);
    }

    //call to the api for the result of the dice
    private function apiDice($dice, $launch)
    {
        $resultat =  file_get_contents("https://www.dejete.com/api?nbde=$dice&tpde=$launch");
        
        return $resultat;
    }

    //save the launchs in the bdd
    private function save(User $user, $resultat, $diceType, $roomId)
    {
        $resultatDecoded = json_decode($resultat);
        $room = $this->getDoctrine()->getRepository(Room::class)->find($roomId);
        //dd($resultatDecoded);
        foreach ($resultatDecoded as $launch) {
            $dice = new Dice;
            $dice->setLaunchBy($user->getPseudo());
            $dice->setResult($launch->value);
            $dice->setType($diceType);
            $dice->setCreatedAt(new DateTime());
            $dice->addRoomId($room);
            $dice->addUserId($user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dice);
            $manager->flush();
        }
    }

    /**
     * @Route("/{id}/save", name="dice_save", methods={"POST"})
     * need result + room id
     */
    public function saveLaunch(User $user, Request $request)
    {
        $data = json_decode($request->getContent());
        $result = $data->result;
        $roomId = $data->room;
        $room = $this->getDoctrine()->getRepository(Room::class)->find($roomId);

        $dice = new Dice;
        $dice->setLaunchBy($user->getPseudo());
        $dice->setResult($result);
        $dice->setType("6");
        $dice->setCreatedAt(new DateTime());
        $dice->addRoomId($room);
        $dice->addUserId($user);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($dice);
        $manager->flush();

        return $this->json(200);
    }
}