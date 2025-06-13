<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PizzaDTO
{
    #[SerializedName('id')]
    public int $id;

    #[SerializedName('title')]
    public string $title;

    #[SerializedName('image')]
    public string $image;

    #[SerializedName('price')]
    public float $price;

    #[SerializedName('ok_celiacs')]
    public bool $okCeliacs;

    #[SerializedName('ingredients')]
    public array $ingredients;

    public function __construct(int $id, string $title, string $image, float $price, bool $okCeliacs, array $ingredients)
    {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->price = $price;
        $this->okCeliacs = $okCeliacs;
        $this->ingredients = $ingredients;
    }
}
