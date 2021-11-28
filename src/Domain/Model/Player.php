<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\ValidableIntInterface;
use App\Domain\Exception\UnknownPlayerException;

/**
 * @psalm-immutable
 */
abstract class Player implements ValidableIntInterface
{
    public const PLAYER1 = 1;
    public const PLAYER2 = 2;

    public static function validate(int $value): void
    {
        if (!\in_array($value, [self::PLAYER1, self::PLAYER2], false)) {
            throw new UnknownPlayerException(sprintf('unknown player: %d', $value), 400);
        }
    }
}
