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
use App\Models\ErrorDTO;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(private OrderService $orderService, private SerializerInterface $serializer) {}

    #[Route('', name: 'post_order', methods: ['POST'])]
    public function postOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $errorDTO = new ErrorDTO(400, 'Invalid JSON');
            return $this->json($errorDTO, 400);
        }

        try {
            if (
                empty($data['payment']['payment_type']) ||
                empty($data['payment']['number']) ||
                empty($data['delivery_time']) ||
                empty($data['delivery_address']) ||
                !isset($data['pizzas_order']) || !is_array($data['pizzas_order']) || count($data['pizzas_order']) === 0
            ) {
                $errorDTO = new ErrorDTO(400, 'Missing or invalid fields in request body.');
                return $this->json($errorDTO, 400);
            }

            $paymentDTO = new PaymentDTO(
                $data['payment']['payment_type'],
                $data['payment']['number']
            );

            $pizzasOrder = [];
            foreach ($data['pizzas_order'] as $po) {
                if (empty($po['pizza_id']) || empty($po['quantity']) || !is_int($po['pizza_id']) || !is_int($po['quantity']) || $po['quantity'] <= 0) {
                    $errorDTO = new ErrorDTO(400, 'Each pizza order must include valid pizza_id (int) and quantity (int > 0).');
                    return $this->json($errorDTO, 400);
                }

                $pizzasOrder[] = new PizzaOrderInputDTO(
                    $po['pizza_id'],
                    $po['quantity']
                );
            }

            $orderInputDTO = new OrderInputDTO(
                $pizzasOrder,
                $data['delivery_time'],
                $data['delivery_address'],
                $paymentDTO
            );

            $orderOutput = $this->orderService->createOrder($orderInputDTO);

            return $this->json($orderOutput);
        } catch (\Throwable $e) {
            $errorDTO = new ErrorDTO(500, 'Server error: ' . $e->getMessage());
            return $this->json($errorDTO, 500);
        }
    }
}
