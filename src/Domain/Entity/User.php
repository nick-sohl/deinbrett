<?php

namespace DeinBrett\Domain\Entity;

class User
{
    public int $id = 0;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $created_at = '';

    public function getId(): int { return $this->id; }
    public function getFirstName(): string { return $this->first_name; }
    public function getLastName(): string { return $this->last_name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): string { return $this->created_at; }
}
