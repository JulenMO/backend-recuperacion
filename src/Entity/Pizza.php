<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaRepository::class)]
class Pizza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $okCeliacs = null;

    #[ORM\OneToMany(mappedBy: 'pizza', targetEntity: PizzaIngredient::class, cascade: ['persist', 'remove'])]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'pizza', targetEntity: PizzaOrder::class)]
    private Collection $pizzaOrders;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->pizzaOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }
    public function getPrice(): ?float
    {
        return $this->price;
    }
    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }
    public function isOkCeliacs(): ?bool
    {
        return $this->okCeliacs;
    }
    public function setOkCeliacs(bool $okCeliacs): static
    {
        $this->okCeliacs = $okCeliacs;
        return $this;
    }
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }
    public function addIngredient(PizzaIngredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setPizza($this);
        }
        return $this;
    }
    public function removeIngredient(PizzaIngredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            if ($ingredient->getPizza() === $this) {
                $ingredient->setPizza(null);
            }
        }
        return $this;
    }
    public function getPizzaOrders(): Collection
    {
        return $this->pizzaOrders;
    }
    public function addPizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if (!$this->pizzaOrders->contains($pizzaOrder)) {
            $this->pizzaOrders->add($pizzaOrder);
            $pizzaOrder->setPizza($this);
        }
        return $this;
    }
    public function removePizzaOrder(PizzaOrder $pizzaOrder): static
    {
        if ($this->pizzaOrders->removeElement($pizzaOrder)) {
            if ($pizzaOrder->getPizza() === $this) {
                $pizzaOrder->setPizza(null);
            }
        }
        return $this;
    }
}
