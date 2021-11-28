<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Application\Helper\JsonHelper;
use App\Domain\Model\Board;
use PHPUnit\Framework\TestCase;

final class BoardTest extends TestCase
{
    public function testBoardHasSizeThreeXThree(): void
    {
        $board = new Board();
        $this->assertCount(3 * 3, $board->getCells());
    }

    public function testBoardIsStorableAsJson(): void
    {
        $toEncode = new Board([2, 1, 2, 1, 2, 1, 1, 2, 1]);
        $json = json_encode($toEncode);
        $data = JsonHelper::toArray($json);
        $fromData = Board::jsonUnserialize($data);

        $this->assertEquals($toEncode, $fromData);
    }
}
