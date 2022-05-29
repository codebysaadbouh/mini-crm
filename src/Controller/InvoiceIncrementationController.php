<?php

namespace App\Controller;
use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Doctrine\Persistence\ObjectManager;


class InvoiceIncrementationController extends AbstractController
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(Invoice $data) : Invoice
    {
        $data->setChrono($data->getChrono() + 1);

        $this->manager->flush();

        dd($data);
    }
}
