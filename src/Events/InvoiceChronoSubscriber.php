<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class InvoiceChronoSubscriber implements EventSubscriberInterface
{

    private Security $security;
    private InvoiceRepository $repositoryInvoice;
    public function __construct(Security $security, InvoiceRepository $repositoryInvoice)
    {
        $this->security = $security;
        $this->repositoryInvoice = $repositoryInvoice;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['giveChronoToInvoices', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function giveChronoToInvoices(ViewEvent $event)
    {
        $invoice = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if($invoice instanceof Invoice && $method === 'POST'){
            $nextChrono = $this->repositoryInvoice->findNextChrono($this->security->getUser());
            // dd($nextChrono);
            $invoice->setChrono($nextChrono);


            // TODO: A déplacer dans un e classe dédiée
            if(empty($invoice->getSentAt())){
                $invoice->setSentAt(new \DateTime());
            }
        }
    }
}