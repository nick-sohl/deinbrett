<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Entity\Board;

class CartService
{
    private const SESSION_KEY = 'deinbrett_cart';

    public function add(Board $board): void
    {
        $cart = $this->get();
        $id   = $board->id;

        if (isset($cart[$id])) {
            // each board is unique/handcrafted — max qty 1
            return;
        }

        $cart[$id] = [
            'board_id' => $id,
            'name'     => $board->name,
            'slug'     => $board->slug,
            'price'    => $board->price,
            'quantity' => 1,
            'type'     => 'shop',
        ];

        $_SESSION[self::SESSION_KEY] = $cart;
    }

    public function addCustom(string $name, float $price, array $config): string
    {
        $cart = $this->get();
        $key  = 'custom_' . substr(md5(serialize($config)), 0, 8);

        $cart[$key] = [
            'cart_key' => $key,
            'board_id' => null,
            'name'     => $name,
            'price'    => $price,
            'quantity' => 1,
            'type'     => 'custom',
            'config'   => $config,
        ];

        $_SESSION[self::SESSION_KEY] = $cart;
        return $key;
    }

    public function remove(int|string $boardId): void
    {
        $cart = $this->get();
        unset($cart[$boardId]);
        $_SESSION[self::SESSION_KEY] = $cart;
    }

    public function items(): array
    {
        return array_values($this->get());
    }

    public function count(): int
    {
        return count($this->get());
    }

    public function total(): float
    {
        return array_sum(array_column($this->items(), 'price'));
    }

    public function has(int $boardId): bool
    {
        return isset($this->get()[$boardId]);
    }

    public function clear(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    private function get(): array
    {
        return $_SESSION[self::SESSION_KEY] ?? [];
    }
}
