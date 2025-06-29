<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class CartItem
{
    public function __construct(
        readonly public string $uuid,
        readonly public string $productUuid,
        readonly public float  $price,
        public int             $quantity,
    )
    {
    }
}
