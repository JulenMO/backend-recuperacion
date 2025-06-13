<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PizzaOrderOutputDTO
{
    #[SerializedName('quantity')]
    public int $quantity;

    #[SerializedName('pizza_type')]
    public PizzaDTO $pizza;

    public function __construct(int $quantity, PizzaDTO $pizza)
    {
        $this->quantity = $quantity;
        $this->pizza = $pizza;
    }
}
