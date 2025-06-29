<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Http\JsonResponse;
use Raketa\BackendTestTask\Service\ProductService;
use Raketa\BackendTestTask\View\ProductsView;

class ProductController
{

    public function __construct(
        private ProductService $productService,
        private ProductsView $productsVew
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        $status = 200;

        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);
            $products = $this->productService->getCategoryProducts($rawRequest['category'] ?? '');
            $response->getBody()->write(
                json_encode(
                    $this->productsVew->toArray($products),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        } catch (Exception) {
            $status = 500;
            $response->getBody()->write(
                json_encode([])
            );
        }

        return $response->withStatus($status);
    }
}