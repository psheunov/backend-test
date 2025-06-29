<?php

namespace Raketa\BackendTestTask\Repository;

use Raketa\BackendTestTask\Domain\Cart;

interface CartRepositoryInterface
{
    public function getCurrentCart(): ?Cart;
    public function save(Cart $cart): void;
}