<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Application\Helper\JsonHelper;
use App\Domain\Model\Board;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGameIsStorableAsJson(): void
    {
        $toEncode = new Board([2, 1, 2, 1, 2, 1, 1, 2, 1]);
        $json = json_encode($toEncode);
        $data = JsonHelper::toArray($json);
        $fromData = Board::jsonUnserialize($data);

        $this->assertEquals($toEncode, $fromData);
    }
}
