<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Service;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartRepositoryInterface;
use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

class CartService
{
    private Cart $cart;

    public function __construct(
        private CartRepositoryInterface    $cartRepository,
        private ProductRepositoryInterface $productRepository
    )
    {
        $this->cart = $this->cartRepository->getCurrentCart();
    }

    /**
     * @param string $productUuid
     * @param int $quantity
     * @return void
     */
    public function addToCart(string $productUuid, int $quantity): void
    {
        $product = $this->productRepository->getByUuid($productUuid);

        $this->cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity
        ));

        $this->cartRepository->save($this->cart);
    }

    /**
     * @return Product[]
     */
    public function getCartProducts(): array
    {
        return $this->productRepository->getByUuids(array_keys($this->cart->getItems()));
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }
}