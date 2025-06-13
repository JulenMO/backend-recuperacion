<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentDTO
{
    #[SerializedName('payment_type')]
    #[Assert\NotBlank]
    #[Assert\Choice(['cash', 'card'])]
    public string $paymentType;

    #[SerializedName('number')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 20)]
    public string $number;

    public function __construct(string $paymentType, string $number)
    {
        $this->paymentType = $paymentType;
        $this->number = $number;
    }
}
