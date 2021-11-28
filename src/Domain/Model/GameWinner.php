<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\ValidableIntInterface;
use App\Domain\Exception\UnknownGameWinnerException;

/**
 * @psalm-immutable
 */
abstract class GameWinner implements ValidableIntInterface
{
    public const NONE = -1;
    public const DRAW = 0;
    public const PLAYER1 = 1;
    public const PLAYER2 = 2;

    /** @param-template T */
    public static function validate(int $value): void
    {
        if (!\in_array($value, [self::NONE, self::DRAW, self::PLAYER1, self::PLAYER2], false)) {
            throw new UnknownGameWinnerException(sprintf('unknown winner player: %d', $value), 400);
        }
    }
}
