<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Entity\Product;
use DeinBrett\Application\Port\Repository;

class ProductService
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll(Product::class);
    }
}
