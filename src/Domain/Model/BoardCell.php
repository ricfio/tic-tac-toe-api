<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\ValidableIntInterface;
use App\Domain\Exception\UnknownBoardCellException;

/**
 * @psalm-immutable
 */
abstract class BoardCell implements ValidableIntInterface
{
    public static function validate(int $value): void
    {
        if (($value < 0) || ($value > (Board::SIZE - 1))) {
            throw new UnknownBoardCellException(sprintf('unknown board cell: %d', $value), 400);
        }
    }
}
