<?php

namespace App\Domain;

class Member
{
    private string $id;
    private string $fullName;
    private string $documentNumber;

    public function __construct(string $id, string $fullName, string $documentNumber)
    {
        $this->id = $id;
        $this->fullName = trim($fullName);
        $this->documentNumber = trim($documentNumber);

        if (empty($this->fullName)) {
            throw new \InvalidArgumentException("El nombre completo no puede estar vacío.");
        }
        if (empty($this->documentNumber)) {
            throw new \InvalidArgumentException("El número de documento no puede estar vacío.");
        }
    }

    public function id(): string { return $this->id; }
    public function fullName(): string { return $this->fullName; }
    public function documentNumber(): string { return $this->documentNumber; }
}