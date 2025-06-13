<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PizzaOrderInputDTO
{
    #[SerializedName('pizza_id')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $pizzaId;

    #[SerializedName('quantity')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $quantity;

    public function __construct(int $pizzaId, int $quantity)
    {
        $this->pizzaId = $pizzaId;
        $this->quantity = $quantity;
    }
}
