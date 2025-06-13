<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\PizzaOrder;
use App\Models\OrderInputDTO;
use App\Models\OrderOutputDTO;
use App\Models\PizzaOrderOutputDTO;
use App\Models\PizzaDTO;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private EntityManagerInterface $em;
    private PizzaService $pizzaService;

    public function __construct(EntityManagerInterface $em, PizzaService $pizzaService)
    {
        $this->em = $em;
        $this->pizzaService = $pizzaService;
    }

    public function createOrder(OrderInputDTO $orderInputDTO): OrderOutputDTO
    {
        $order = new Order();

        $deliveryTime = \DateTime::createFromFormat('H:i', $orderInputDTO->deliveryTime);
        if (!$deliveryTime) {
            throw new \InvalidArgumentException('Invalid delivery time format. Expected H:i');
        }
        $order->setDeliveryTime($deliveryTime);
        $order->setDeliveryAddress($orderInputDTO->deliveryAddress);
        $order->setPaymentType($orderInputDTO->payment->paymentType);
        $order->setPaymentNumber($orderInputDTO->payment->number);

        foreach ($orderInputDTO->pizzasOrder as $pizzaOrderInput) {
            $pizza = $this->pizzaService->findPizzaById($pizzaOrderInput->pizzaId);
            if (!$pizza) {
                throw new \InvalidArgumentException('Pizza not found with id: ' . $pizzaOrderInput->pizzaId);
            }
            $pizzaOrder = new PizzaOrder();
            $pizzaOrder->setPizza($pizza);
            $pizzaOrder->setQuantity($pizzaOrderInput->quantity);
            $order->addPizzaOrder($pizzaOrder);
        }

        $this->em->persist($order);
        $this->em->flush();

        $pizzaOrderOutputDTOs = [];
        foreach ($order->getPizzaOrders() as $pizzaOrder) {
            $pizza = $pizzaOrder->getPizza();

            $ingredientNames = [];
            foreach ($pizza->getIngredients() as $ingredient) {
                $ingredientNames[] = $ingredient->getName();
            }

            $pizzaDTO = new PizzaDTO(
                $pizza->getId(),
                $pizza->getTitle(),
                $pizza->getImage(),
                $pizza->getPrice(),
                $pizza->isOkCeliacs(),
                $ingredientNames
            );

            $pizzaOrderOutputDTOs[] = new PizzaOrderOutputDTO(
                $pizzaOrder->getQuantity(),
                $pizzaDTO
            );
        }

        return new OrderOutputDTO($order->getId(), $pizzaOrderOutputDTOs);
    }
}
