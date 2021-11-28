<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Model\Game;

interface GameRepositoryInterface
{
    public function has(string $key): bool;

    public function get(string $key): ?Game;

    public function set(string $key, Game $game): void;

    public function del(string $key): void;
}
