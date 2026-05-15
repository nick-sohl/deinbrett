<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Application\Service\CartService;
use DeinBrett\Application\Service\OrderService;
use DeinBrett\Presentation\Helper\Csrf;
use DeinBrett\Presentation\View\View;

class CheckoutController
{
    public function __construct(
        private CartService  $cart,
        private OrderService $orderService,
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
        $this->sendConfirmationEmails($order);

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

    private function sendConfirmationEmails(\DeinBrett\Domain\Entity\Order $order): void
    {
        $shopEmail = 'info@deinbrett.ch';
        $headers   = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
        $clean     = fn(string $s): string => str_replace(["\r", "\n"], '', $s);

        // Email to customer
        $subject = $clean("Deine Bestellung bei DeinBrett – {$order->reference}");
        $body    = $this->buildCustomerEmail($order);
        mail($clean($order->email), $subject, $body, $headers . "From: {$shopEmail}\r\nReply-To: {$shopEmail}");

        // Email to shop
        $shopSubject = $clean("Neue Bestellung {$order->reference} – {$order->fullName()}");
        $shopBody    = $this->buildShopEmail($order);
        mail($shopEmail, $shopSubject, $shopBody, $headers . "From: {$shopEmail}");
    }

    private function buildCustomerEmail(\DeinBrett\Domain\Entity\Order $order): string
    {
        $name  = htmlspecialchars($order->fullName());
        $ref   = htmlspecialchars($order->reference);
        $total = number_format($order->total, 2, '.', "'");
        return <<<HTML
        <p>Hallo {$name},</p>
        <p>vielen Dank für deine Bestellung bei DeinBrett. Wir haben sie erhalten und melden uns innerhalb von 24 Stunden.</p>
        <p><strong>Bestellreferenz:</strong> {$ref}<br>
        <strong>Betrag:</strong> CHF {$total}</p>
        <p>Lieferzeit: 4–6 Wochen · Handgefertigt in der Schweiz</p>
        <p>Bei Fragen antworte einfach auf diese E-Mail.<br>Dein DeinBrett-Team</p>
        HTML;
    }

    private function buildShopEmail(\DeinBrett\Domain\Entity\Order $order): string
    {
        $name  = htmlspecialchars($order->fullName());
        $email = htmlspecialchars($order->email);
        $ref   = htmlspecialchars($order->reference);
        $total = number_format($order->total, 2, '.', "'");
        $addr  = htmlspecialchars("{$order->address}, {$order->zip} {$order->city}");
        return <<<HTML
        <p><strong>Neue Bestellung eingegangen!</strong></p>
        <p>Referenz: {$ref}<br>
        Kunde: {$name} ({$email})<br>
        Lieferadresse: {$addr}<br>
        Betrag: CHF {$total}<br>
        Zahlung: TWINT ✓</p>
        HTML;
    }
}
