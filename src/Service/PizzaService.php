<?php

namespace App\Service;

use App\Entity\Pizza;
use App\Entity\PizzaIngredient;
use App\Models\PizzaDTO;
use App\Models\PizzaIngredientDTO;
use Doctrine\ORM\EntityManagerInterface;

class PizzaService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllPizzas(?string $nameFilter = null, ?string $ingredientFilter = null): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p', 'i')
            ->from(Pizza::class, 'p')
            ->leftJoin('p.ingredients', 'i');

        if ($nameFilter) {
            $qb->andWhere('p.title LIKE :name')
                ->setParameter('name', '%' . $nameFilter . '%');
        }

        if ($ingredientFilter) {
            $qb->andWhere('i.name LIKE :ingredient')
                ->setParameter('ingredient', '%' . $ingredientFilter . '%');
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

    public function findPizzaById(int $id): ?Pizza
    {
        return $this->em->getRepository(Pizza::class)->find($id);
    }
}
