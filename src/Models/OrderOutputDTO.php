<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderOutputDTO
{
    #[SerializedName('id')]
    public int $id;

    #[SerializedName('pizzas_order')]
    public array $pizzasOrder;

    public function __construct(int $id, array $pizzasOrder)
    {
        $this->id = $id;
        $this->pizzasOrder = $pizzasOrder;
    }
}
