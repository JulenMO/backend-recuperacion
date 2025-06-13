<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PizzaOrderInputDTO
{
    #[SerializedName('pizza_id')]
    public int $pizzaId;

    #[SerializedName('quantity')]
    public int $quantity;

    public function __construct(int $pizzaId, int $quantity)
    {
        $this->pizzaId = $pizzaId;
        $this->quantity = $quantity;
    }
}
