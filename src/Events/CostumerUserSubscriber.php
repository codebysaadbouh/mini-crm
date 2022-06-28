<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class CostumerUserSubscriber implements EventSubscriberInterface
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForCostumer', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForCostumer(ViewEvent $event)
    {
        $customer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (($customer instanceof Customer) && ($method === 'POST')) {
            // Récupérer l'utilisateur connecté
            $user = $this->security->getUser();
            // Assigner l'utilisateur connecté au client
            $customer->setUser($user);
        }
    }
}