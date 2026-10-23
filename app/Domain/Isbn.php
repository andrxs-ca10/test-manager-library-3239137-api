<?php

namespace App\Domain;

class Isbn
{
    private string $value;

    public function __construct(string $value)
    {
        $clean = trim($value);
        if (!preg_match('/^\d{13}$/', $clean)) {
            throw new \InvalidArgumentException("El ISBN debe tener exactamente 13 dígitos numéricos.");
        }
        $this->value = $clean;
    }

    public function value(): string
    {
        return $this->value;
    }
}