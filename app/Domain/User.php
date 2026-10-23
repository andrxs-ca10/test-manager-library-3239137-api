<?php

namespace App\Domain;

class User
{
    private string $id;
    private string $username;
    private string $passwordHash;
    private string $role;

    public function __construct(string $id, string $username, string $passwordHash, string $role)
    {
        $cleanUsername = strtolower(trim($username));
        if (empty($cleanUsername)) {
            throw new \InvalidArgumentException("El nombre de usuario no puede estar vacío.");
        }

        $validRoles = ['admin', 'librarian'];
        if (!in_array($role, $validRoles, true)) {
            throw new \InvalidArgumentException("Rol inválido.");
        }

        $this->id = $id;
        $this->username = $cleanUsername;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }

    public function id(): string { return $this->id; }
    public function username(): string { return $this->username; }
    public function passwordHash(): string { return $this->passwordHash; }
    public function role(): string { return $this->role; }
}