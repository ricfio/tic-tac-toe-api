<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\JsonUnserializable;
use App\Domain\Exception\InvalidBoardException;
use JsonSerializable;

class Board implements JsonSerializable, JsonUnserializable
{
    public const SIZE = 3 * 3;

    /**
     * Marks in the cells of the game board.
     *
     * @var int[]
     */
    private array $marks;

    /**
     * Paths of the lines (line's cell position).
     *
     * @var list<int[]>
     */
    private array $paths;

    /**
     * @param int[] $marks
     */
    public function __construct(array $marks = null)
    {
        $this->marks = self::initMarks($marks);
        $this->paths = self::initPaths();
    }

    public static function jsonUnserialize(array $data): self
    {
        /** @var int[] $data */
        return new self(
            marks: $data,
        );
    }

    public function jsonSerialize(): array
    {
        return $this->marks;
    }

    /** @return int[] */
    public function getCells(): array
    {
        /** @var int[] $cells */
        $cells = array_keys($this->marks);

        return $cells;
    }

    /** @return int[] */
    public function getLines(): array
    {
        /** @var int[] $lines */
        $lines = array_keys($this->paths);

        return $lines;
    }

    /** @return int[] */
    public function getMarks(): array
    {
        return $this->marks;
    }

    /** @return int[] */
    public function getLineMarks(int $line): array
    {
        $marks = [];
        foreach ($this->paths[$line] as $cell) {
            $marks[] = $this->marks[$cell];
        }

        return $marks;
    }

    public function isCompleted(): bool
    {
        foreach ($this->marks as $mark) {
            if (CellMark::NONE === $mark) {
                return false;
            }
        }

        return true;
    }

    public function hasCellUnmarked(int $cell): bool
    {
        return 0 === $this->marks[$cell];
    }

    public function setCellMarked(int $cell, int $mark): void
    {
        $this->marks[$cell] = $mark;
    }

    /**
     * @param int[] $marks
     *
     * @return int[]
     */
    private static function initMarks(array $marks = null): array
    {
        if (null === $marks) {
            return [
                0, 0, 0,
                0, 0, 0,
                0, 0, 0,
            ];
        }

        if (self::SIZE != \count($marks)) {
            throw new InvalidBoardException(sprintf('invalid board size: %d', \count($marks)), 400);
        }
        foreach ($marks as $mark) {
            CellMark::validate($mark);
        }

        return $marks;
    }

    /** @return list<int[]> */
    private static function initPaths(): array
    {
        return [
            // horizontal lines (path)
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8],
            // vertical lines (path)
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8],
            // diagonal lines (path)
            [0, 4, 8],
            [2, 4, 6],
        ];
    }
}
