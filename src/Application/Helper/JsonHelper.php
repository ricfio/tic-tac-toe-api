<?php

declare(strict_types=1);

namespace App\Application\Helper;

use App\Application\Exception\ParsingJsonException;
use Exception;

abstract class JsonHelper
{
    /**
     * @throws Exception
     *
     * @return array<string,mixed|null> $data
     */
    public static function toArray(string $json): array
    {
        /** @var array<string,mixed>|null */
        $data = json_decode($json, true);

        if (null === $data) {
            throw new ParsingJsonException('Error in parsing json: '.$json, 400);
        }

        return $data;
    }
}
