<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Application\Service\CartService;
use DeinBrett\Application\Service\OrderMailer;
use DeinBrett\Application\Service\OrderService;
use DeinBrett\Presentation\Helper\Csrf;
use DeinBrett\Presentation\View\View;

class CheckoutController
{
    public function __construct(
        private CartService  $cart,
        private OrderService $orderService,
        private OrderMailer  $mailer,
    ) {}

    public function index(): void
    {
        if ($this->cart->isEmpty()) {
            header('Location: /shop');
            exit;
        }

        $view = new View('checkout', 'index', [
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
            'cartCount' => $this->cart->count(),
            'showHero'  => false,
            'errors'    => [],
            'old'       => [],
        ]);
        $view->renderFull();
    }

    public function submit(): void
    {
        Csrf::verify();
        if ($this->cart->isEmpty()) {
            header('Location: /shop');
            exit;
        }

        $fields = ['first_name', 'last_name', 'email', 'address', 'city', 'zip'];
        $data   = [];
        $errors = [];

        foreach ($fields as $f) {
            $data[$f] = trim($_POST[$f] ?? '');
            if ($data[$f] === '') {
                $errors[$f] = 'Pflichtfeld';
            }
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ungültige E-Mail-Adresse';
        }

        $data['phone']   = trim($_POST['phone']   ?? '');
        $data['country'] = trim($_POST['country'] ?? 'CH');
        $data['notes']   = trim($_POST['notes']   ?? '');

        if (strlen($data['notes']) > 500) {
            $errors['notes'] = 'Maximal 500 Zeichen';
        }

        if (!empty($errors)) {
            $view = new View('checkout', 'index', [
                'cartItems' => $this->cart->items(),
                'cartTotal' => $this->cart->total(),
                'cartCount' => $this->cart->count(),
                'showHero'  => false,
                'errors'    => $errors,
                'old'       => $data,
            ]);
            $view->renderFull();
            return;
        }

        if (!empty($_SESSION['pending_order'])) {
            header('Location: /checkout/twint');
            exit;
        }

        $order = $this->orderService->create($data, $this->cart->items());
        $_SESSION['pending_order'] = $order->reference;

        $this->mailer->sendNewOrderNotification($order);

        header('Location: /checkout/twint');
        exit;
    }

    public function twint(): void
    {
        $reference = $_SESSION['pending_order'] ?? '';
        if (!$reference) {
            header('Location: /shop');
            exit;
        }

        $order = $this->orderService->findByReference($reference);
        if (!$order) {
            header('Location: /shop');
            exit;
        }

        $items = $this->orderService->itemsForOrder($order->id);

        $view = new View('checkout', 'twint', [
            'order'    => $order,
            'items'    => $items,
            'showHero' => false,
        ]);
        $view->renderFull();
    }

    public function twintConfirm(): void
    {
        Csrf::verify();
        $reference = $_SESSION['pending_order'] ?? '';
        if (!$reference) {
            header('Location: /shop');
            exit;
        }

        $order = $this->orderService->markPaid($reference);
        if (!$order) {
            header('Location: /shop');
            exit;
        }

        $this->cart->clear();
        $this->mailer->sendStatusUpdate($order, 'pending');

        session_regenerate_id(true);
        $_SESSION['confirmed_order'] = $reference;
        unset($_SESSION['pending_order']);

        header('Location: /checkout/confirm');
        exit;
    }

    public function confirm(): void
    {
        $reference = $_SESSION['confirmed_order'] ?? '';
        unset($_SESSION['confirmed_order']);

        if (!$reference) {
            header('Location: /shop');
            exit;
        }

        $order = $this->orderService->findByReference($reference);
        if (!$order) {
            header('Location: /shop');
            exit;
        }

        $items = $this->orderService->itemsForOrder($order->id);

        $view = new View('checkout', 'confirm', [
            'order'    => $order,
            'items'    => $items,
            'showHero' => false,
        ]);
        $view->renderFull();
    }
}
