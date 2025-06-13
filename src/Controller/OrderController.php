<?php

namespace App\Controller;

use App\Models\OrderInputDTO;
use App\Models\OrderOutputDTO;
use App\Models\PaymentDTO;
use App\Models\PizzaOrderInputDTO;
use App\Models\PizzaOrderOutputDTO;
use App\Models\PizzaDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\OrderService;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(private OrderService $orderService, private SerializerInterface $serializer) {}

    #[Route('', name: 'post_order', methods: ['POST'])]
    public function postOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $this->json(['code' => 400, 'description' => 'Invalid JSON'], 400);
        }

        try {
            $paymentDTO = new PaymentDTO(
                $data['payment']['payment_type'] ?? '',
                $data['payment']['number'] ?? ''
            );

            $pizzasOrder = [];
            foreach ($data['pizzas_order'] ?? [] as $po) {
                $pizzasOrder[] = new PizzaOrderInputDTO(
                    $po['pizza_id'] ?? 0,
                    $po['quantity'] ?? 0
                );
            }

            $orderInputDTO = new OrderInputDTO(
                $pizzasOrder,
                $data['delivery_time'] ?? '',
                $data['delivery_address'] ?? '',
                $paymentDTO
            );

            $orderOutput = $this->orderService->createOrder($orderInputDTO);

            return $this->json($orderOutput);
        } catch (\Throwable $e) {
            return $this->json(['code' => 500, 'description' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
