<?php

namespace DeinBrett\Domain\Entity;

class Order
{
    public int    $id             = 0;
    public string $reference      = '';
    public string $status         = 'pending';
    public string $first_name     = '';
    public string $last_name      = '';
    public string $email          = '';
    public string $phone          = '';
    public string $address        = '';
    public string $city           = '';
    public string $zip            = '';
    public string $country        = 'CH';
    public string $notes          = '';
    public float  $subtotal       = 0.0;
    public float  $shipping       = 0.0;
    public float  $total          = 0.0;
    public string $payment_method = 'twint';
    public string $payment_status = 'pending';
    public string $created_at     = '';

    public function fullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
