<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter\Repository;

use App\Application\Repository\GameRepositoryInterface;
use App\Domain\Model\Game;

class GameRepositorySession implements GameRepositoryInterface
{
    private const TABLENAME = 'games';

    /** @var Game[] */
    private array $games = [];

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct()
    {
        if (\PHP_SESSION_NONE === session_status()) {
            session_start();
            if (isset($_SESSION)) {
                if ((!\array_key_exists(self::TABLENAME, $_SESSION)) || (!\is_array($_SESSION[self::TABLENAME]))) {
                    /** @var Game[] */
                    $this->games = &$_SESSION[self::TABLENAME];
                }
            }
        }
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->games);
    }

    public function get(string $key): ?Game
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->games[$key];
    }

    public function set(string $key, Game $game): void
    {
        $this->games[$key] = $game;
    }

    public function del(string $key): void
    {
        unset($this->games[$key]);
    }
}
