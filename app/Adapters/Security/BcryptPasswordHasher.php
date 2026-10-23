<?php

namespace App\Adapters\Security;

use App\Application\Ports\PasswordHasherInterface;
use Illuminate\Support\Facades\Hash;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        return Hash::make($password);
    }

    public function verify(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}