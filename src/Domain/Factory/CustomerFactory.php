<?php

namespace Raketa\BackendTestTask\Domain\Factory;

use Raketa\BackendTestTask\Domain\Customer;
use Ramsey\Uuid\Uuid;

class CustomerFactory
{
    public static function createFromSession(): Customer
    {
        return new Customer(
            id: $_SESSION['customer_id'] ?? Uuid::uuid4()->toString(),
            firstName: $_SESSION['firstName'] ?? '',
            lastName: $_SESSION['lastName'] ?? '',
            middleName: $_SESSION['middleName'] ?? '',
            email: $_SESSION['email'] ?? ''
        );
    }
}