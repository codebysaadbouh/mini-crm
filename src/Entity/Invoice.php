<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter; // ordonner nos résultats  ("amount" & "sentAt")
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\InvoiceIncrementationController;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ApiResource(
 *     subresourceOperations={
 *          "api_costumers_invoices_get_subresource"={
 *              "normalization_context"={"groups"={"invoices_subresources"}}
 *          }
 *     },
 *     attributes={
 *         "pagination_enabled"=true,
 *         "pagination_items_per_page"=40,
 *         "order"={"amount": "desc"}
 *     },
 *     normalizationContext={
 *         "groups"={"invoices_read"}
 *     },
 *     itemOperations={"GET","PUT", "DELETE","increment"={
 *          "method"="POST",
 *          "path"="/invoices/{id}/increment",
 *          "controller"=InvoiceIncrementationController::class,
 *          "openapi_context"={
 *              "summary"="Incrémente une facture",
 *              "description"="Incrémente le chrono d'une facture donnée"
 *          }
 *        }
 *     },
 * )
 * @ApiFilter(OrderFilter::class, properties={"amount", "sentAt"})
 * @ApiFilter(SearchFilter::class, properties={"customer.firstName": "partial", "customer.lastName": "partial", "sentAt": "partial", "costumer.id": "exact"})
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "costumers_read", "invoices_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoices_read", "costumers_read", "invoices_subresources"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"invoices_read", "costumers_read"})
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoices_read", "costumers_read", "invoices_subresources"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Costumer::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoices_read"})
     */
    private $costumer;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "costumers_read", "invoices_subresources"})
     */
    private $chrono;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCostumer(): ?Costumer
    {
        return $this->costumer;
    }

    public function setCostumer(?Costumer $costumer): self
    {
        $this->costumer = $costumer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }

    /**
     * Permet de récupérer le USER à qui appartient l'invoice
     * @Groups({"invoices_read", "invoices_subresources"})
     * @return User
     */
    public function getUser() : User {
        return $this->getCostumer()->getUser();
    }
}
