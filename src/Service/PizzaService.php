<?php

namespace App\Service;

use App\Entity\Pizza;
use App\Models\PizzaDTO;
use App\Models\PizzaIngredientDTO;
use Doctrine\ORM\EntityManagerInterface;

class PizzaService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function getPizzas(?string $nameFilter = null, ?array $ingredientsFilter = null): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p', 'i')
            ->from(Pizza::class, 'p')
            ->leftJoin('p.ingredients', 'i');

        if ($nameFilter !== null) {
            $qb->andWhere('p.title LIKE :name')
                ->setParameter('name', '%' . $nameFilter . '%');
        }

        if ($ingredientsFilter !== null && count($ingredientsFilter) > 0) {
            $ingredientCount = count($ingredientsFilter);

            $qb->andWhere(
                '(SELECT COUNT(DISTINCT i2.id) FROM App\Entity\PizzaIngredient i2 WHERE i2.pizza = p AND i2.name IN (:ingredients)) = :count'
            )
                ->setParameter('ingredients', $ingredientsFilter)
                ->setParameter('count', $ingredientCount);
        }

        $pizzas = $qb->getQuery()->getResult();

        $pizzaDTOs = [];

        foreach ($pizzas as $pizza) {
            $ingredientsDTO = [];
            foreach ($pizza->getIngredients() as $ingredient) {
                $ingredientsDTO[] = new PizzaIngredientDTO($ingredient->getName());
            }
            $pizzaDTOs[] = new PizzaDTO(
                $pizza->getId(),
                $pizza->getTitle(),
                $pizza->getImage(),
                $pizza->getPrice(),
                $pizza->isOkCeliacs(),
                $ingredientsDTO
            );
        }

        return $pizzaDTOs;
    }
}
