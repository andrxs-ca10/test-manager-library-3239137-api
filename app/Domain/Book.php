<?php

namespace App\Domain;

use DateTimeImmutable;
use InvalidArgumentException;
use DomainException;

class Book
{
    private string $id;
    private string $title;
    private string $author;
    private Isbn $isbn;
    private string $categoryId;
    private int $totalCopies;
    private int $availableCopies;
    private ?string $coverKey;
    private ?DateTimeImmutable $deletedAt;

    public function __construct(
        string $id,
        string $title,
        string $author,
        Isbn $isbn,
        string $categoryId,
        int $totalCopies,
        int $availableCopies,
        ?string $coverKey = null,
        ?DateTimeImmutable $deletedAt = null
    ) {
        if ($totalCopies < 1) {
            throw new InvalidArgumentException("El total de ejemplares debe ser al menos 1.");
        }
        if ($availableCopies < 0 || $availableCopies > $totalCopies) {
            throw new InvalidArgumentException("Los ejemplares disponibles deben estar entre 0 y el total.");
        }

        $this->id = $id;
        $this->title = trim($title);
        $this->author = trim($author);
        $this->isbn = $isbn;
        $this->categoryId = $categoryId;
        $this->totalCopies = $totalCopies;
        $this->availableCopies = $availableCopies;
        $this->coverKey = $coverKey;
        $this->deletedAt = $deletedAt;
    }

    public function withdraw(int $amount = 1): void
    {
        if ($this->availableCopies - $amount < 0) {
            throw new DomainException("No hay suficientes ejemplares disponibles.");
        }
        $this->availableCopies -= $amount;
    }

    public function restock(int $amount = 1): void
    {
        if ($this->availableCopies + $amount > $this->totalCopies) {
            throw new DomainException("Los ejemplares disponibles no pueden superar el total.");
        }
        $this->availableCopies += $amount;
    }

    public function updateTotal(int $newTotal): void
    {
        if ($newTotal < 1) {
            throw new InvalidArgumentException("El total de ejemplares debe ser al menos 1.");
        }
        $diff = $newTotal - $this->totalCopies;
        $newAvailable = $this->availableCopies + $diff;

        if ($newAvailable < 0) {
            throw new DomainException("Cambiar el total dejaría los ejemplares disponibles por debajo de 0.");
        }

        $this->totalCopies = $newTotal;
        $this->availableCopies = $newAvailable;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    // Getters
    public function id(): string { return $this->id; }
    public function title(): string { return $this->title; }
    public function author(): string { return $this->author; }
    public function isbn(): Isbn { return $this->isbn; }
    public function categoryId(): string { return $this->categoryId; }
    public function totalCopies(): int { return $this->totalCopies; }
    public function availableCopies(): int { return $this->availableCopies; }
    public function coverKey(): ?string { return $this->coverKey; }
    public function deletedAt(): ?DateTimeImmutable { return $this->deletedAt; }
    public function isDeleted(): bool { return $this->deletedAt !== null; }
}