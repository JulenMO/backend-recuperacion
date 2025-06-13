<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderInputDTO
{
    #[SerializedName('pizzas_order')]
    public array $pizzasOrder;

    #[SerializedName('delivery_time')]
    public string $deliveryTime;

    #[SerializedName('delivery_address')]
    public string $deliveryAddress;

    #[SerializedName('payment')]
    public PaymentDTO $payment;

    public function __construct(array $pizzasOrder, string $deliveryTime, string $deliveryAddress, PaymentDTO $payment)
    {
        $this->pizzasOrder = $pizzasOrder;
        $this->deliveryTime = $deliveryTime;
        $this->deliveryAddress = $deliveryAddress;
        $this->payment = $payment;
    }
}
