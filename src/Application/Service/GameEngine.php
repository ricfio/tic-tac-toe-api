<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Exception\GameStatusException;
use App\Domain\Exception\InvalidMoveCellException;
use App\Domain\Exception\InvalidMoveTurnException;
use App\Domain\Model\Board;
use App\Domain\Model\CellMark;
use App\Domain\Model\Game;
use App\Domain\Model\GameMove;
use App\Domain\Model\GameStatus;
use App\Domain\Model\GameWinner;
use App\Domain\Model\Player;
use App\Domain\Model\Scan;

class GameEngine
{
    public function buildMoveRandom(Game $game): GameMove
    {
        if ($game->hasStatusCompleted()) {
            throw new GameStatusException('The game has already been completed', 400);
        }
        $unmarkedCells = [];
        foreach ($game->getBoardCells() as $cell) {
            if ($game->hasCellUnmarked($cell)) {
                $unmarkedCells[] = $cell;
            }
        }
        if (0 == \count($unmarkedCells)) {
            throw new InvalidMoveCellException('There are no more free cells on the board', 400);
        }

        $random = random_int(0, \count($unmarkedCells) - 1);
        $cell = $unmarkedCells[$random];

        return new GameMove(
            turn: $game->getTurn(),
            cell: $cell,
        );
    }

    public function applyMove(Game $game, GameMove $move): void
    {
        $this->validateMove($game, $move);
        $game->setCellMarked($move->getCell(), $move->getTurn());

        $scan = $this->scanBoard($game->getBoard());
        $winner = $this->detectWinner($game, $scan);
        $game->updateData(
            turn: $this->detectTurn($game),
            winner: $winner,
            status: $this->detectStatus($game, $winner),
        );
    }

    private function validateMove(Game $game, GameMove $move): void
    {
        $gameTurn = $game->getTurn();
        if ($move->getTurn() !== $gameTurn) {
            throw new InvalidMoveTurnException('Waiting for the turn of the player: '.$gameTurn, 400);
        }
        $moveCell = $move->getCell();
        if (!$game->hasCellUnmarked($moveCell)) {
            throw new InvalidMoveCellException('Cell is busy because it is already marked: '.$moveCell, 400);
        }
    }

    private function scanBoard(Board $board): Scan
    {
        $lines = $board->getLines();
        foreach ($lines as $line) {
            $marks = $board->getLineMarks($line);
            // Check if there is only one mark in all cells of the line
            if (1 === \count(array_flip($marks))) {
                /** @var int */
                $mark = reset($marks);

                return new Scan(
                    mark: $mark,
                    line: $line,
                );
            }
        }

        return new Scan();
    }

    private function detectTurn(Game $game): int
    {
        return match ($game->getTurn()) {
            Player::PLAYER1 => Player::PLAYER2,
            default         => Player::PLAYER1,
        };
    }

    private function detectWinner(Game $game, Scan $scan): int
    {
        if ($scan->hasWinner()) {
            return match ($scan->getMark()) {
                CellMark::MARK_X  => GameWinner::PLAYER1,
                CellMark::MARK_O  => GameWinner::PLAYER2,
                default           => GameWinner::NONE,
            };
        }

        return match ($game->hasBoardCompleted()) {
            false => GameWinner::NONE,
            true  => GameWinner::DRAW,
        };
    }

    private function detectStatus(Game $game, int $winner): int
    {
        if (GameWinner::NONE !== $winner) {
            return GameStatus::COMPLETED;
        }

        return match ($game->hasBoardCompleted()) {
            false => GameStatus::PENDING,
            true  => GameStatus::COMPLETED,
        };
    }
}
