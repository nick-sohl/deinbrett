<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

use DeinBrett\Application\Service\CartService;
use DeinBrett\Application\Service\OrderService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Controller\CartController;
use DeinBrett\Presentation\Controller\CheckoutController;
use DeinBrett\Presentation\Controller\HomeController;
use DeinBrett\Presentation\Controller\KalkulatorController;
use DeinBrett\Presentation\Controller\ShopController;
use DeinBrett\Presentation\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/helpers.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Ensure CSRF token exists in session for this request
\DeinBrett\Presentation\Helper\Csrf::generate();

// Infrastructure
$sqlRepository = new SqliteRepository();

// Services
$cartService  = new CartService();
$orderService = new OrderService($sqlRepository);

// Controllers
$homeController       = new HomeController();
$kalkulatorController = new KalkulatorController();
$shopController       = new ShopController($sqlRepository, $cartService);
$cartController       = new CartController($sqlRepository, $cartService);
$checkoutController   = new CheckoutController($cartService, $orderService);

// Router
$router = new Router();

$router->get('/',                      [$homeController,     'index']);
$router->get('/shop',                  [$shopController,     'index']);
$router->post('/cart/add',             [$cartController,     'add']);
$router->post('/cart/add-custom',      [$cartController,     'addCustom']);
$router->post('/cart/remove',          [$cartController,     'remove']);
$router->get('/checkout',              [$checkoutController, 'index']);
$router->post('/checkout',             [$checkoutController, 'submit']);
$router->get('/checkout/twint',        [$checkoutController, 'twint']);
$router->post('/checkout/twint',       [$checkoutController, 'twintConfirm']);
$router->get('/checkout/confirm',      [$checkoutController, 'confirm']);
$router->post('/api/kalkulator/calculate', [$kalkulatorController, 'calculate']);
$router->get('/api/wood',              [$kalkulatorController, 'woodInfo']);
$router->get('/api/size',              [$kalkulatorController, 'sizeInfo']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
