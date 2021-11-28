<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\ValidableIntInterface;
use App\Domain\Exception\UnknownGameStatusException;

/**
 * @psalm-immutable
 */
abstract class GameStatus implements ValidableIntInterface
{
    public const PENDING = 0;
    public const COMPLETED = 1;

    public static function validate(int $value): void
    {
        if (!\in_array($value, [self::PENDING, self::COMPLETED], false)) {
            throw new UnknownGameStatusException(sprintf('unknown game status: %d', $value), 400);
        }
    }
}
