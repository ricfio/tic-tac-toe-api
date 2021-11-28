<?php

declare(strict_types=1);

namespace App\Tests\Functional\Application\Service;

use App\Application\Exception\GameNotFoundException;
use App\Application\Helper\TokenHelper;
use App\Application\Service\GameService;
use App\ContainerFactory;
use App\Domain\Exception\InvalidMoveCellException;
use App\Domain\Exception\InvalidMoveTurnException;
use App\Domain\Model\Game;
use App\Domain\Model\GameMove;
use App\Domain\Model\Player;
use PHPUnit\Framework\TestCase;

final class GameServiceTest extends TestCase
{
    protected GameService $gameService;
    protected Game $game;
    protected string $token;

    protected function setUp(): void
    {
        $container = ContainerFactory::create();
        /** @var GameService */
        $this->gameService = $container->get(GameService::class);
        $this->game = $this->gameService->start();
        $this->token = $this->game->getToken();
    }

    public function testGameStartReturnAGame(): void
    {
        $this->assertInstanceOf(Game::class, $this->game);
    }

    public function testMakeMoveDoNotChangeTheToken(): void
    {
        $game = $this->gameService->start();
        $token = $game->getToken();

        $move = new GameMove(Player::PLAYER1, 0);
        $game = $this->gameService->makeMove($token, $move);
        $this->assertSame($token, $game->getToken());

        $move = new GameMove(Player::PLAYER2, 1);
        $game = $this->gameService->makeMove($token, $move);
        $this->assertSame($token, $game->getToken());
    }

    public function testMakeMoveRequireExistantToken(): void
    {
        $this->expectException(GameNotFoundException::class);
        $token = TokenHelper::build();
        $move = new GameMove(Player::PLAYER1, 0);
        $this->gameService->makeMove($token, $move);
    }

    /**
     * @dataProvider invalidMoveTurnProvider
     *
     * @param GameMove[] $moves
     */
    public function testMakeMoveWithInvalidTurnThrowAnException(array $moves): void
    {
        $this->expectException(InvalidMoveTurnException::class);
        $game = $this->gameService->start();
        $token = $game->getToken();
        foreach ($moves as $move) {
            $this->gameService->makeMove($token, $move);
        }
    }

    /** @return array<string,list<GameMove[]>> */
    public function invalidMoveTurnProvider(): array
    {
        return [
            'P:2'     => [[new GameMove(Player::PLAYER2, 0)]],
            'P:1,1'   => [[new GameMove(Player::PLAYER1, 0), new GameMove(Player::PLAYER1, 1)]],
            'P:1,2,2' => [[new GameMove(Player::PLAYER1, 0), new GameMove(Player::PLAYER2, 1), new GameMove(Player::PLAYER2, 2)]],
        ];
    }

    /**
     * @dataProvider invalidMoveCellProvider
     *
     * @param GameMove[] $moves
     */
    public function testMakeMoveWithInvalidCellThrowAnException(array $moves): void
    {
        $this->expectException(InvalidMoveCellException::class);
        $game = $this->gameService->start();
        $token = $game->getToken();
        foreach ($moves as $move) {
            $this->gameService->makeMove($token, $move);
        }
    }

    /** @return array<string,list<GameMove[]>> */
    public function invalidMoveCellProvider(): array
    {
        return [
            'C:0,0'   => [[new GameMove(Player::PLAYER1, 0), new GameMove(Player::PLAYER2, 0)]],
            'C:0,1,0' => [[new GameMove(Player::PLAYER1, 0), new GameMove(Player::PLAYER2, 1), new GameMove(Player::PLAYER1, 0)]],
        ];
    }
}
