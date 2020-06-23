<?php
namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class ExceptionListener
{
    public function onKernelException(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
        //dd($user->getId());
        $event->setData(['id'=>$user->getId(),'pseudo'=>$user->getPseudo(),'email'=>$user->getEmail(), 'icon'=>$user->getIcon()]);
    }
}