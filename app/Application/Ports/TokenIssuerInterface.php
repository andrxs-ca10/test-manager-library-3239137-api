<?php

namespace App\Application\Ports;

interface TokenIssuerInterface
{
    public function issue(string $sub, string $role): string;
}