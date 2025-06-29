<?php

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Domain\Enums\PaymentMethod;

final class CartFactory
{
    public static function createEmpty(Customer $customer): Cart
    {
        return new Cart(
            uuid: session_id(),
            customer: $customer,
            paymentMethod: PaymentMethod::CART,
            items: []
        );
    }
}