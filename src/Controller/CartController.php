<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Http\JsonResponse;
use Raketa\BackendTestTask\Service\CartService;
use Raketa\BackendTestTask\View\CartView;

class CartController
{
    /**
     * @param CartService $cartService
     * @param CartView $cartView
     */
    public function __construct(
        private CartService $cartService,
        private CartView    $cartView,
    )
    {
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function add(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        $status = 200;
        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);
            $this->cartService->addToCart($rawRequest['productUuid'] ?? null, $rawRequest['quantity'] ?? null);
            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'success',
                        'cart' => $this->cartView->toArray($this->cartService->getCart(), $this->cartService->getCartProducts())
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        } catch (Exception $e) {
            $status = 500;
            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Ошибка при добавлении элемента в корзину'
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        }

        return $response->withStatus($status);
    }

    /**
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->cartService->getCart();
        $response->getBody()->write(
            json_encode(
                $this->cartView->toArray($cart, $this->cartService->getCartProducts()),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response->withStatus(200);

    }
}