<?php

namespace App\Application\Ports;

use App\Domain\User;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?User;
    public function save(User $user): void;
}