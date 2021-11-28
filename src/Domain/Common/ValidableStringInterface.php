<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface ValidableStringInterface
{
    public static function validate(string $value): void;
}
