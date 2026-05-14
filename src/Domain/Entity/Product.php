<?php

namespace DeinBrett\Domain\Entity;

class Product
{
    public int $id = 0;
    public string $name = '';
    public string $description = '';
    public float $price = 0.0;
    public int $quantity = 0;
    public string $created_at = '';

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getPrice(): float { return $this->price; }
    public function getQuantity(): int { return $this->quantity; }
    public function getCreatedAt(): string { return $this->created_at; }
}
