<?php

namespace App\Domain;

class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("La cantidad no puede ser negativa.");
        }
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}