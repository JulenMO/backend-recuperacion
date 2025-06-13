<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $deliveryTime = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(length: 50)]
    private ?string $paymentType = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentNumber = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: PizzaOrder::class, cascade: ['persist'])]
    private Collection $pizzaOrders;

    public function __construct()
    {
        $this->pizzaOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDeliveryTime(): ?\DateTimeInterface
    {
        return $this->deliveryTime;
    }
    public function setDeliveryTime(\DateTimeInterface $deliveryTime): static
    {
        $this->deliveryTime = $deliveryTime;
        return $this;
    }
    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }
    public function setDeliveryAddress(string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }
    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }
    public function setPaymentType(string $paymentType): static
    {
        $this->paymentType = $paymentType;
        return $this;
    }
    public function getPaymentNumber(): ?string
    {
        return $this->paymentNumber;
    }
    public function setPaymentNumber(string $paymentNumber): static
    {
        $this->paymentNumber = $paymentNumber;
        return $this;
    }
    public function getPizzaOrders(): Collection
    {
        return $this->pizzaOrders;
    }
    public function addPizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if (!$this->pizzaOrders->contains($pizzaOrder)) {
            $this->pizzaOrders->add($pizzaOrder);
            $pizzaOrder->setOrder($this);
        }
        return $this;
    }
    public function removePizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if ($this->pizzaOrders->removeElement($pizzaOrder)) {
            if ($pizzaOrder->getOrder() === $this) {
                $pizzaOrder->setOrder(null);
            }
        }
        return $this;
    }
}
