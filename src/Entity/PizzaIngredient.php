<?php

namespace App\Entity;

use App\Repository\PizzaIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaIngredientRepository::class)]
class PizzaIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pizza $pizza = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getPizza(): ?Pizza
    {
        return $this->pizza;
    }
    public function setPizza(?Pizza $pizza): static
    {
        $this->pizza = $pizza;
        return $this;
    }
}
