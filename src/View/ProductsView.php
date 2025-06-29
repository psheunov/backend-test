<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;

readonly class ProductsView
{
    /**
     * @param Product[] $products
     * @return array
     */
    public function toArray(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->getId(),
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ];
        }

        return $result;
    }
}
