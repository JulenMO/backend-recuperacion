<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Valid;

class OrderInputDTO
{
    #[SerializedName('pizzas_order')]
    #[Assert\NotBlank]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    public array $pizzasOrder;

    #[SerializedName('delivery_time')]
    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'Y-m-d H:i:s')]
    public string $deliveryTime;

    #[SerializedName('delivery_address')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255)]
    public string $deliveryAddress;

    #[SerializedName('payment')]
    #[Assert\NotNull]
    #[Assert\Valid]
    public PaymentDTO $payment;

    public function __construct(array $pizzasOrder, string $deliveryTime, string $deliveryAddress, PaymentDTO $payment)
    {
        $this->pizzasOrder = $pizzasOrder;
        $this->deliveryTime = $deliveryTime;
        $this->deliveryAddress = $deliveryAddress;
        $this->payment = $payment;
    }
}
