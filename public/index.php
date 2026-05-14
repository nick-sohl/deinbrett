<?php

use DeinBrett\Application\Service\ProductService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Controller\HomeController;
use DeinBrett\Presentation\Controller\ProductController;
use DeinBrett\Presentation\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Repository
$sqlRepository = new SqliteRepository();
// Service
$productService = new ProductService($sqlRepository);
// Controller
$homeController = new HomeController();
$productController = new ProductController($productService);
// Router
$router = new Router();
//    Define Routes
$router->get("/", [$homeController, "index"]);
$router->get("/products", [$productController, "index"]);
//    Dispatch -> METHOD + PATH
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
