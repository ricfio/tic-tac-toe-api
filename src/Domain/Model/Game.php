<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Common\JsonUnserializable;
use JsonSerializable;

class Game implements JsonSerializable, JsonUnserializable
{
    private Board $board;

    public function __construct(
        private string $token,
        ?Board $board = null,
        private int $turn = Player::PLAYER1,
        private int $winner = GameWinner::NONE,
        private int $status = GameStatus::PENDING,
    ) {
        $this->board = $board ?? new Board();
        Player::validate($turn);
        GameWinner::validate($winner);
        GameStatus::validate($status);
    }

    public static function jsonUnserialize(array $data): self
    {
        return new self(
            token: (string) $data['token'],
            board: Board::jsonUnserialize((array) $data['board']),
            turn: (int) $data['turn'],
            winner: (int) $data['winner'],
            status: (int) $data['status'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'token'  => $this->token,
            'board'  => $this->board->jsonSerialize(),
            'turn'   => $this->turn,
            'winner' => $this->winner,
            'status' => $this->status,
        ];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function getWinner(): int
    {
        return $this->winner;
    }

    /** @return int[] */
    public function getBoardCells(): array
    {
        return $this->board->getCells();
    }

    public function hasCellUnmarked(int $cell): bool
    {
        return $this->board->hasCellUnmarked($cell);
    }

    public function hasBoardCompleted(): bool
    {
        return $this->board->isCompleted();
    }

    public function hasStatusCompleted(): bool
    {
        return GameStatus::COMPLETED === $this->status;
    }

    public function setCellMarked(int $cell, int $mark): void
    {
        $this->board->setCellMarked($cell, $mark);
    }

    public function updateData(int $turn, int $winner, int $status): void
    {
        $this->turn = $turn;
        $this->winner = $winner;
        $this->status = $status;
    }
}
