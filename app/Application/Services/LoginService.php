<?php

namespace App\Application\Services;

use App\Application\Ports\UserRepositoryInterface;
use App\Application\Ports\PasswordHasherInterface;
use App\Application\Ports\TokenIssuerInterface;
use DomainException;

class LoginService
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private TokenIssuerInterface $tokenIssuer;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        TokenIssuerInterface $tokenIssuer
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenIssuer = $tokenIssuer;
    }

    public function execute(string $username, string $password): array
    {
        $cleanUsername = strtolower(trim($username));
        $user = $this->userRepository->findByUsername($cleanUsername);

        if (!$user || !$this->passwordHasher->verify($password, $user->passwordHash())) {
            throw new DomainException("Credenciales inválidas.");
        }

        $token = $this->tokenIssuer->issue($user->id(), $user->role());

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id(),
                'username' => $user->username(),
                'role' => $user->role(),
            ]
        ];
    }
}