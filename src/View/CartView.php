<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\Entity\Product;

readonly class CartView
{
    /**
     * @param Cart $cart
     * @param array $products
     * @return array
     */
    public function toArray(Cart $cart, array $products): array
    {
        return [
            'uuid' => $cart->uuid,
            'customer' => $this->formatCustomer($cart),
            'payment_method' => $cart->paymentMethod->value,
            'items' => $this->formatItems($cart, $products),
            'total' => $this->calculateTotal($cart)
        ];
    }

    private function formatCustomer(Cart $cart): array
    {
        $customer = $cart->customer;

        return [
            'id' => $customer->id,
            'name' => implode(' ', [
                $customer->lastName,
                $customer->firstName,
                $customer->middleName,
            ]),
            'email' => $customer->email
        ];
    }

    /**
     * @param Cart $cart
     * @param array $products
     * @return array
     */
    private function formatItems(Cart $cart, array $products): array
    {
        return array_map(fn(CartItem $item) => $this->formatItem($item, $products[$item->productUuid] ?? null), $cart->getItems());
    }

    /**
     * @param CartItem $item
     * @param Product|null $product
     * @return array
     */
    private function formatItem(CartItem $item, ?Product $product): array
    {
        return [
            'uuid' => $item->uuid,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'total' => $item->price * $item->quantity,
            'product' => [
                'id' => $product?->getId(),
                'uuid' => $product?->getUuid(),
                'name' => $product?->getName(),
                'thumbnail' => $product?->getThumbnail(),
                'price' => $product?->getPrice()
            ]
        ];
    }

    /**
     * @param Cart $cart
     * @return float
     */
    private function calculateTotal(Cart $cart): float
    {
        return array_reduce(
            $cart->getItems(),
            fn(float $sum, CartItem $item) => $sum + ($item->price * $item->quantity), 0.0
        );
    }
}
