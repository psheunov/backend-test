<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

use Raketa\BackendTestTask\Domain\Enums\PaymentMethod;

final class Cart
{
    /**
     * @param string $uuid
     * @param Customer $customer
     * @param PaymentMethod $paymentMethod
     * @param array $items
     */
    public function __construct(
        readonly public string $uuid,
        readonly public Customer $customer,
        readonly public PaymentMethod $paymentMethod,
        private array $items,
    ) {
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param CartItem $item
     * @return void
     */
    public function addItem(CartItem $item): void
    {
        if (isset($this->items[$item->productUuid])) {
            ($this->items[$item->productUuid])->quantity += $item->quantity;
        } else {
            $this->items[$item->productUuid] = $item;
        }
    }
}
