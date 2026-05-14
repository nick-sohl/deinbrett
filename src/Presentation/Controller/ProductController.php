<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Application\Service\ProductService;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): void
    {
        return;
    }
}
