<?php

declare(strict_types=1);

namespace App\Domain\Model;

/** @psalm-immutable */
class GameMove
{
    public function __construct(
        private int $turn,
        private int $cell,
    ) {
        Player::validate($turn);
        BoardCell::validate($cell);
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function getCell(): int
    {
        return $this->cell;
    }
}
