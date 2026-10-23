<?php

namespace App\Application\Services;

use App\Application\Ports\UserRepositoryInterface;
use App\Application\Ports\PasswordHasherInterface;
use App\Domain\User;
use DomainException;
use Illuminate\Support\Str;

class CreateLibrarianService
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(string $username, string $password): string
    {
        $cleanUsername = strtolower(trim($username));
        $existing = $this->userRepository->findByUsername($cleanUsername);
        
        if ($existing) {
            throw new DomainException("El nombre de usuario ya está en uso.");
        }

        $hash = $this->passwordHasher->hash($password);
        $userId = (string) Str::uuid();

        $user = new User($userId, $cleanUsername, $hash, 'librarian');
        $this->userRepository->save($user);

        return $userId;
    }
}