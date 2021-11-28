<?php

declare(strict_types=1);

namespace App\Application\Helper;

use App\Application\Exception\InvalidTokenException;
use App\Domain\Common\ValidableStringInterface;

class TokenHelper implements ValidableStringInterface
{
    public const TOKEN_LENGHT = 32;

    public static function build(): string
    {
        $bytes = random_bytes(self::TOKEN_LENGHT / 2);

        return bin2hex($bytes);
    }

    public static function validate(string $value): void
    {
        if (self::TOKEN_LENGHT != \strlen($value)) {
            throw new InvalidTokenException(sprintf('invalid token length: %d', \strlen($value)), 400);
        }
    }
}
