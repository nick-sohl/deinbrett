<?php

namespace DeinBrett\Domain\Entity;

class Board
{
    public int    $id           = 0;
    public string $name         = '';
    public string $slug         = '';
    public string $tagline      = '';
    public string $description  = '';
    public string $wood_type    = 'eiche';
    public string $construction = 'stirnholz';
    public string $size         = 'L';
    public string $extras       = '[]';
    public float  $price        = 0.0;
    public int    $stock        = 0;
    public int    $featured     = 0;
    public string $image_path   = '';
    public string $created_at   = '';

    public function extrasArray(): array
    {
        return json_decode($this->extras, true) ?? [];
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }
}
