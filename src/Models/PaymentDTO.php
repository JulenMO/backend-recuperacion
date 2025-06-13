<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentDTO
{
    #[SerializedName('payment_type')]
    public string $paymentType;

    #[SerializedName('number')]
    public string $number;

    public function __construct(string $paymentType, string $number)
    {
        $this->paymentType = $paymentType;
        $this->number = $number;
    }
}
