<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\ValidableIntInterface;
use App\Domain\Exception\UnknownCellMarkException;

/**
 * @psalm-immutable
 */
abstract class CellMark implements ValidableIntInterface
{
    public const NONE = 0;
    public const MARK_X = 1;
    public const MARK_O = 2;

    public static function validate(int $value): void
    {
        if (!\in_array($value, [self::NONE, self::MARK_X, self::MARK_O], false)) {
            throw new UnknownCellMarkException(sprintf('unknown cell mark: %d', $value), 400);
        }
    }
}
