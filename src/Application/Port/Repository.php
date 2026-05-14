<?php

namespace DeinBrett\Application\Port;

interface Repository
{
    public function findAll(string $entity): array;
}
