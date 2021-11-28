<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Exception\GameNotFoundException;
use App\Application\Exception\GameStatusException;
use App\Application\Helper\TokenHelper;
use App\Application\Repository\GameRepositoryInterface;
use App\Domain\Model\Game;
use App\Domain\Model\GameMove;

class GameService
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
        private GameEngine $gameEngine,
    ) {
    }

    public function start(): Game
    {
        $token = TokenHelper::build();
        $game = new Game($token);
        $this->gameRepository->set($token, $game);

        return $game;
    }

    public function resume(string $token): Game
    {
        $game = $this->findGame($token);

        return $game;
    }

    public function makeMove(string $token, GameMove $move): Game
    {
        $game = $this->findGame($token);
        $this->gameEngine->applyMove($game, $move);
        $this->gameRepository->set($token, $game);

        return $game;
    }

    public function autoMove(string $token): Game
    {
        $game = $this->findGame($token);
        $move = $this->gameEngine->buildMoveRandom($game);
        $this->gameEngine->applyMove($game, $move);
        $this->gameRepository->set($token, $game);

        return $game;
    }

    private function findGame(string $token): Game
    {
        $game = $this->gameRepository->get($token);
        if (null === $game) {
            throw new GameNotFoundException('Game not found', 404);
        }
        if ($game->hasStatusCompleted()) {
            throw new GameStatusException('The game has already been completed', 400);
        }

        return $game;
    }
}
