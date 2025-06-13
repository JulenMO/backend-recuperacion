<?php

namespace App\Controller;

use App\Models\ErrorDTO;
use App\Service\PizzaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pizza')]
class PizzaController extends AbstractController
{
    public function __construct(private PizzaService $pizzaService) {}

    #[Route('', name: 'get_pizzas', methods: ['GET'])]
    public function getPizzas(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $ingredients = $request->query->get('ingredients');

        if ($name !== null && !is_string($name)) {
            $errorDTO = new ErrorDTO(400, 'Parameter "name" must be a string.');
            return $this->json($errorDTO, 400);
        }

        if ($ingredients !== null && !is_string($ingredients)) {
            $errorDTO = new ErrorDTO(400, 'Parameter "ingredients" must be a string.');
            return $this->json($errorDTO, 400);
        }

        $ingredientsArray = null;
        if ($ingredients !== null) {
            $ingredientsArray = array_filter(array_map('trim', explode(',', $ingredients)));
            if (count($ingredientsArray) === 0) {
                $ingredientsArray = null;
            }
        }

        $pizzaDTOs = $this->pizzaService->getPizzas($name, $ingredientsArray);

        return $this->json($pizzaDTOs);
    }
}
