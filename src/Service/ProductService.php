<?php

namespace Raketa\BackendTestTask\Service;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepositoryInterface;

class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param string $category
     * @return Product[]
     */
    public function getCategoryProducts(string $category): array
    {
        return $this->productRepository->getByCategory($category);
    }
}