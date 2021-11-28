<?php

declare(strict_types=1);

namespace App\Infrastructure\Port\Controller;

use App\Application\Exception\InvalidMoveException;
use App\Application\Helper\JsonHelper;
use App\Application\Helper\TokenHelper;
use App\Application\Service\GameService;
use App\Domain\Model\GameMove;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class GameController
{
    private GameService $gameService;

    public function __construct(ContainerInterface $container)
    {
        /** @var GameService */
        $this->gameService = $container->get(GameService::class);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function startGame(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $game = $this->gameService->start();

        return $response->withStatus(200)->withJson([
            'token' => $game->getToken(),
        ]);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resumeGame(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $token = self::parseToken($args);
        $game = $this->gameService->resume($token);

        return $response->withStatus(200)->withJson($game);
    }

    public function makeMove(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $token = self::parseToken($args);
        $move = self::parseMove($request);
        $game = $this->gameService->makeMove($token, $move);

        return $response->withStatus(200)->withJson($game);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function autoMove(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $token = self::parseToken($args);
        $game = $this->gameService->autoMove($token);

        return $response->withStatus(200)->withJson($game);
    }

    private static function parseToken(array $args): string
    {
        $token = (string) $args['token'];
        TokenHelper::validate($token);

        return $token;
    }

    private static function parseMove(ServerRequestInterface $request): GameMove
    {
        $json = $request->getBody()->getContents();
        $data = JsonHelper::toArray($json);
        if (!\is_int($data['turn'])) {
            throw new InvalidMoveException(sprintf('invalid turn, must be an integer value: %s', (string) $data['turn']), 400);
        }
        if (!\is_int($data['cell'])) {
            throw new InvalidMoveException(sprintf('invalid cell, must be an integer value: %s', (string) $data['cell']), 400);
        }

        return new GameMove(
            turn: $data['turn'],
            cell: $data['cell'],
        );
    }
}
