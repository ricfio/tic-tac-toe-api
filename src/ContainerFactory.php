<?php

declare(strict_types=1);

namespace App;

use App\Application\Repository\GameRepositoryInterface;
use App\Application\Service\GameEngine;
use App\Application\Service\GameService;
use App\Infrastructure\Adapter\Repository\GameRepositoryFolder;
use DI\Container;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        // $storagePath = __DIR__.'/../temp';
        $storagePath = '/tmp/tic-tac-toe-api';
        $gameRepository = new GameRepositoryFolder($storagePath);
        $gameEngine = new GameEngine();
        $gameService = new GameService($gameRepository, $gameEngine);

        $container = new Container();
        $container->set(GameRepositoryInterface::class, fn () => $gameRepository);
        $container->set(GameEngine::class, fn () => $gameEngine);
        $container->set(GameService::class, fn () => $gameService);

        return $container;
    }
}
