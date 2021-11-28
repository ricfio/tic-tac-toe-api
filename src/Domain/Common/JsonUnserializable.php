<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface JsonUnserializable
{
    public static function jsonUnserialize(array $data): self;
}
