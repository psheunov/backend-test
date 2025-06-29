<?php

namespace Raketa\BackendTestTask\Domain\Enums;

enum PaymentMethod: string
{
    case  CART = 'cart';
    case CASH = 'cash';
}
