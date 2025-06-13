<?php

namespace App\Controller;

use App\Models\PizzaDTO;
use App\Models\PizzaIngredientDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PizzaService;

#[Route('/pizza')]
class PizzaController extends AbstractController
{
    public function __construct(private PizzaService $pizzaService) {}

    #[Route('', name: 'get_pizzas', methods: ['GET'])]
    public function getPizzas(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $ingredients = $request->query->get('ingredients');
        $ingredientsArray = null;

        if ($ingredients !== null) {
            $ingredientsArray = array_filter(array_map('trim', explode(',', $ingredients)));
        }

        $pizzas = $this->pizzaService->getPizzas($name, $ingredientsArray);

        $pizzaDTOs = array_map(function ($pizza) {
            $ingredientsDTO = array_map(fn($ingredient) => new PizzaIngredientDTO($ingredient), $pizza['ingredients']);
            return new PizzaDTO(
                $pizza['id'],
                $pizza['title'],
                $pizza['image'],
                $pizza['price'],
                $pizza['ok_celiacs'],
                $ingredientsDTO
            );
        }, $pizzas);

        return $this->json($pizzaDTOs);
    }
}
