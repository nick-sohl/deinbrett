<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Application\Service\CartService;
use DeinBrett\Application\Service\ConfiguratorService;
use DeinBrett\Application\Service\OrderMailer;
use DeinBrett\Application\Service\OrderService;
use DeinBrett\Application\Service\ProductService;
use DeinBrett\Application\Service\SettingsService;
use DeinBrett\Domain\Data\BoardData;
use DeinBrett\Infrastructure\Adapter\MailAdapter;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Controller\Admin\AuthController as AdminAuthController;
use DeinBrett\Presentation\Controller\Admin\ConstructionController as AdminConstructionController;
use DeinBrett\Presentation\Controller\Admin\DashboardController as AdminDashboardController;
use DeinBrett\Presentation\Controller\Admin\ExtraController as AdminExtraController;
use DeinBrett\Presentation\Controller\Admin\OrderController as AdminOrderController;
use DeinBrett\Presentation\Controller\Admin\ProductController as AdminProductController;
use DeinBrett\Presentation\Controller\Admin\SettingsController as AdminSettingsController;
use DeinBrett\Presentation\Controller\Admin\SizeController as AdminSizeController;
use DeinBrett\Presentation\Controller\Admin\WoodTypeController as AdminWoodTypeController;
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

\DeinBrett\Presentation\Helper\Csrf::generate();

// Infrastructure
$sqlRepository = new SqliteRepository();

$appEnv        = $_ENV['APP_ENV']       ?? getenv('APP_ENV') ?: 'production';
$mailFrom      = $_ENV['MAIL_FROM']     ?? getenv('MAIL_FROM') ?: 'info@deinbrett.ch';
$mailFromName  = $_ENV['MAIL_FROM_NAME']?? getenv('MAIL_FROM_NAME') ?: 'DeinBrett';
$adminEmailEnv = $_ENV['ADMIN_EMAIL']   ?? getenv('ADMIN_EMAIL') ?: 'info@deinbrett.ch';

$mailAdapter = new MailAdapter($mailFrom, $mailFromName, logOnly: $appEnv === 'local');

// Services
$configurator   = new ConfiguratorService($sqlRepository);
BoardData::bind($configurator); // legacy static facade
$authService    = new AuthService($sqlRepository);
$cartService    = new CartService();
$orderService   = new OrderService($sqlRepository);
$productService = new ProductService($sqlRepository);
$settingsSvc    = new SettingsService($sqlRepository);
$orderMailer    = new OrderMailer($mailAdapter, $sqlRepository, $settingsSvc->get('admin_email', $adminEmailEnv));

// Public controllers
$homeController       = new HomeController();
$kalkulatorController = new KalkulatorController();
$shopController       = new ShopController($sqlRepository, $cartService);
$cartController       = new CartController($sqlRepository, $cartService);
$checkoutController   = new CheckoutController($cartService, $orderService, $orderMailer);

// Router
$router = new Router();

// Public routes
$router->get('/',                          fn() => $homeController->index());
$router->get('/shop',                      fn() => $shopController->index());
$router->post('/cart/add',                 fn() => $cartController->add());
$router->post('/cart/add-custom',          fn() => $cartController->addCustom());
$router->post('/cart/remove',              fn() => $cartController->remove());
$router->get('/checkout',                  fn() => $checkoutController->index());
$router->post('/checkout',                 fn() => $checkoutController->submit());
$router->get('/checkout/twint',            fn() => $checkoutController->twint());
$router->post('/checkout/twint',           fn() => $checkoutController->twintConfirm());
$router->get('/checkout/confirm',          fn() => $checkoutController->confirm());
$router->post('/api/kalkulator/calculate', fn() => $kalkulatorController->calculate());
$router->get('/api/wood',                  fn() => $kalkulatorController->woodInfo());
$router->get('/api/size',                  fn() => $kalkulatorController->sizeInfo());

// Admin auth routes (no middleware — login/logout endpoints)
$adminAuth = new AdminAuthController($authService);
$router->get('/admin/login',   fn() => $adminAuth->showLogin());
$router->post('/admin/login',  fn() => $adminAuth->login());
$router->post('/admin/logout', fn() => $adminAuth->logout());

// Lazy-instantiate protected controllers so their constructors (which check auth) only fire when matched
$adminDashboard   = fn() => new AdminDashboardController($authService, $sqlRepository);
$adminProducts    = fn() => new AdminProductController($authService, $productService, $configurator);
$adminOrders      = fn() => new AdminOrderController($authService, $orderService, $orderMailer);
$adminSettings    = fn() => new AdminSettingsController($authService, $settingsSvc);
$adminWoods       = fn() => new AdminWoodTypeController($authService, $sqlRepository);
$adminSizes       = fn() => new AdminSizeController($authService, $sqlRepository);
$adminConstruct   = fn() => new AdminConstructionController($authService, $sqlRepository);
$adminExtras      = fn() => new AdminExtraController($authService, $sqlRepository);

$router->get('/admin',                                     fn($p) => $adminDashboard()->index());
$router->get('/admin/products',                            fn($p) => $adminProducts()->index());
$router->get('/admin/products/new',                        fn($p) => $adminProducts()->create());
$router->post('/admin/products',                           fn($p) => $adminProducts()->store());
$router->get('/admin/products/{id}/edit',                  fn($p) => $adminProducts()->edit($p));
$router->post('/admin/products/{id}/update',               fn($p) => $adminProducts()->update($p));
$router->post('/admin/products/{id}/delete',               fn($p) => $adminProducts()->delete($p));

$router->get('/admin/orders',                              fn($p) => $adminOrders()->index());
$router->get('/admin/orders/export.csv',                   fn($p) => $adminOrders()->exportCsv());
$router->get('/admin/orders/{id}',                         fn($p) => $adminOrders()->show($p));
$router->post('/admin/orders/{id}/status',                 fn($p) => $adminOrders()->updateStatus($p));

$router->get('/admin/options/woods',                       fn($p) => $adminWoods()->index());
$router->get('/admin/options/woods/new',                   fn($p) => $adminWoods()->create());
$router->post('/admin/options/woods',                      fn($p) => $adminWoods()->store());
$router->get('/admin/options/woods/{id}/edit',             fn($p) => $adminWoods()->edit($p));
$router->post('/admin/options/woods/{id}/update',          fn($p) => $adminWoods()->update($p));
$router->post('/admin/options/woods/{id}/delete',          fn($p) => $adminWoods()->delete($p));

$router->get('/admin/options/sizes',                       fn($p) => $adminSizes()->index());
$router->get('/admin/options/sizes/new',                   fn($p) => $adminSizes()->create());
$router->post('/admin/options/sizes',                      fn($p) => $adminSizes()->store());
$router->get('/admin/options/sizes/{id}/edit',             fn($p) => $adminSizes()->edit($p));
$router->post('/admin/options/sizes/{id}/update',          fn($p) => $adminSizes()->update($p));
$router->post('/admin/options/sizes/{id}/delete',          fn($p) => $adminSizes()->delete($p));

$router->get('/admin/options/constructions',               fn($p) => $adminConstruct()->index());
$router->get('/admin/options/constructions/new',           fn($p) => $adminConstruct()->create());
$router->post('/admin/options/constructions',              fn($p) => $adminConstruct()->store());
$router->get('/admin/options/constructions/{id}/edit',     fn($p) => $adminConstruct()->edit($p));
$router->post('/admin/options/constructions/{id}/update',  fn($p) => $adminConstruct()->update($p));
$router->post('/admin/options/constructions/{id}/delete',  fn($p) => $adminConstruct()->delete($p));

$router->get('/admin/options/extras',                      fn($p) => $adminExtras()->index());
$router->get('/admin/options/extras/new',                  fn($p) => $adminExtras()->create());
$router->post('/admin/options/extras',                     fn($p) => $adminExtras()->store());
$router->get('/admin/options/extras/{id}/edit',            fn($p) => $adminExtras()->edit($p));
$router->post('/admin/options/extras/{id}/update',         fn($p) => $adminExtras()->update($p));
$router->post('/admin/options/extras/{id}/delete',         fn($p) => $adminExtras()->delete($p));

$router->get('/admin/settings',                            fn($p) => $adminSettings()->index());
$router->post('/admin/settings',                           fn($p) => $adminSettings()->update());

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
