<?php
namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class ExceptionListener
{
    public function onKernelException(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
        $cookie = Cookie::create('test')
            ->withValue('if this work OMG')
            ->withDomain('.test.com')
            ->withSecure(false);
        $event->getResponse()->headers->setcookie($cookie);
        $event->setData(['id'=>$user->getId(),'pseudo'=>$user->getPseudo(),'email'=>$user->getEmail(), 'icon'=>$user->getIcon()]);
    }
}