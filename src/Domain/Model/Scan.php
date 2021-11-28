<?php

declare(strict_types=1);

namespace App\Domain\Model;

/**
 * @psalm-immutable
 */
class Scan
{
    public function __construct(
        private int $mark = CellMark::NONE,
        private ?int $line = null,
    ) {
    }

    public function getMark(): int
    {
        return $this->mark;
    }

    public function getLine(): int|null
    {
        return $this->line;
    }

    public function hasWinner(): bool
    {
        return null !== $this->line;
    }
}
