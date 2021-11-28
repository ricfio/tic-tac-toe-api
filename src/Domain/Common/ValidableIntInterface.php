<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface ValidableIntInterface
{
    public static function validate(int $value): void;
}
