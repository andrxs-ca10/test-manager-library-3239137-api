<?php

namespace App\Domain;

use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;

class LoanItem
{
    private string $id;
    private string $bookId;
    private string $bookTitle;
    private string $isbn;
    private string $categoryName;
    private DateTimeImmutable $dueAt;
    private ?DateTimeImmutable $returnedAt;

    public function __construct(
        string $id,
        string $bookId,
        string $bookTitle,
        string $isbn,
        string $categoryName,
        DateTimeImmutable $dueAt,
        ?DateTimeImmutable $returnedAt = null
    ) {
        $this->id = $id;
        $this->bookId = $bookId;
        $this->bookTitle = trim($bookTitle);
        $this->isbn = trim($isbn);
        $this->categoryName = trim($categoryName);
        $this->dueAt = $dueAt;
        $this->returnedAt = $returnedAt;
    }

    public function returnItem(DateTimeImmutable $now): void
    {
        if ($this->returnedAt !== null) {
            throw new DomainException("La línea ya fue devuelta.");
        }
        $this->returnedAt = $now;
    }

    public function isReturned(): bool
    {
        return $this->returnedAt !== null;
    }

    public function isOverdue(DateTimeImmutable $now): bool
    {
        return !$this->isReturned() && $this->dueAt < $now;
    }

    // Getters
    public function id(): string { return $this->id; }
    public function bookId(): string { return $this->bookId; }
    public function bookTitle(): string { return $this->bookTitle; }
    public function isbn(): string { return $this->isbn; }
    public function categoryName(): string { return $this->categoryName; }
    public function dueAt(): DateTimeImmutable { return $this->dueAt; }
    public function returnedAt(): ?DateTimeImmutable { return $this->returnedAt; }
}

class Loan
{
    private string $id;
    private string $memberId;
    private string $loanedByUserId;
    private DateTimeImmutable $loanedAt;
    /** @var LoanItem[] */
    private array $items;

    /**
     * @param LoanItem[] $items
     */
    public function __construct(
        string $id,
        string $memberId,
        string $loanedByUserId,
        DateTimeImmutable $loanedAt,
        array $items
    ) {
        if (count($items) < 1 || count($items) > 3) {
            throw new InvalidArgumentException("Un préstamo debe tener entre 1 y 3 líneas.");
        }

        $bookIds = [];
        foreach ($items as $item) {
            if (in_array($item->bookId(), $bookIds, true)) {
                throw new DomainException("No se puede repetir el mismo libro en un préstamo.");
            }
            $bookIds[] = $item->bookId();
        }

        $this->id = $id;
        $this->memberId = $memberId;
        $this->loanedByUserId = $loanedByUserId;
        $this->loanedAt = $loanedAt;
        $this->items = $items;
    }

    public function status(DateTimeImmutable $now): string
    {
        $allReturned = true;
        $hasOverdue = false;

        foreach ($this->items as $item) {
            if (!$item->isReturned()) {
                $allReturned = false;
                if ($item->isOverdue($now)) {
                    $hasOverdue = true;
                }
            }
        }

        if ($allReturned) {
            return 'closed';
        }
        if ($hasOverdue) {
            return 'overdue';
        }
        return 'open';
    }

    // Getters
    public function id(): string { return $this->id; }
    public function memberId(): string { return $this->memberId; }
    public function loanedByUserId(): string { return $this->loanedByUserId; }
    public function loanedAt(): DateTimeImmutable { return $this->loanedAt; }
    /** @return LoanItem[] */
    public function items(): array { return $this->items; }
}