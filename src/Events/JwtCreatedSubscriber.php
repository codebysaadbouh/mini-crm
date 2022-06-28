<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JwtCreatedSubscriber
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {

    }

    public function updateJwtData(JWTCreatedEvent $event){
        $request = $this->requestStack->getCurrentRequest();
        // dd($request);


        // 1.  Get User (Firstname, Lastname, Email)
        $user = $event->getUser();
        //dd($user);

        // 2. Enrichir les dara pour qu'elles contiennent ces données
        $data = $event->getData();
        // dd($event->getData());

        $data['firstname'] = $user->getFirstName();
        $data['lastname'] = $user->getLastName();

        $expiration = new \DateTime('+1 day');
        $expiration->setTime(2, 0, 0);

        $data['exp'] = $expiration->getTimestamp();

        $event->setData($data);
        // dd($event->getData());
    }
}