<?php

namespace App\Controller;

use App\Entity\User;
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
        $resultat = $this->apiDice($params->dice,$params->launch);


        return $this->json($resultat, 200);
    }

    private function apiDice($dice, $launch)
    {
        $resultat =  file_get_contents("https://www.dejete.com/api?nbde=$dice&tpde=$launch");
        
        return $resultat;
    }
}
