<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PizzaIngredientDTO
{
    #[SerializedName('name')]
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
