<?php
namespace App\EventListener;

use App\Entity\Room;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ExceptionListener extends AbstractController
{
    public function onKernelException(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
        //$fullUser = $this->getDoctrine()->getRepository(User::class)->find($userId)->getRooms();
        //$room = $this->getDoctrine()->getRepository(Room::class)->find(1);
        //dd($user->getRoomsGameMaster()->toArray());
        $roomUniqueIdGM = $user->getRoomsGameMaster()->toArray();
        $roomUniqueId = $user->getRooms()->toArray();
        if (empty($roomUniqueIdGM)) {
            $roomUniqueIdGM = "";
            if (empty($roomUniqueId)) {
                $roomId = "";
                $role = 0;
            } else {
                $role = 1;
                $roomId = $roomUniqueId[0]->getUuid();
            }
        } else {
            $role = 2;
            $roomId = $roomUniqueIdGM[0]->getUuid();
        }
        
        
        $event->setData(['id'=>$user->getId(),'pseudo'=>$user->getPseudo(),'email'=>$user->getEmail(), 'icon'=>$user->getIcon(), 'roomId' => $roomId, 'role' => $role]);
    }
}