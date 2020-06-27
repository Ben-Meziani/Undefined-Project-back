<?php
namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class ExceptionListener
{
    public function onKernelException(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
    
        $event->setData(['id'=>$user->getId(),'pseudo'=>$user->getPseudo(),'email'=>$user->getEmail(), 'icon'=>$user->getIcon()]);
    }
}