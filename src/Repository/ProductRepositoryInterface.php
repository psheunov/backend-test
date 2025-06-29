<?php

namespace Raketa\BackendTestTask\Repository;

use Raketa\BackendTestTask\Repository\Entity\Product;

interface ProductRepositoryInterface
{

    public function getByUuid(string $uuid): Product;

    public function getByCategory(string $category): array;
}