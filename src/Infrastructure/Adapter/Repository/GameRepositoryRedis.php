<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter\Repository;

use App\Application\Helper\JsonHelper;
use App\Application\Repository\GameRepositoryInterface;
use App\Domain\Model\Game;
use Redis;
use RuntimeException;

class GameRepositoryRedis implements GameRepositoryInterface
{
    private Redis $redis;

    public function __construct(private string $host)
    {
        $this->redis = new Redis();
    }

    public function has(string $key): bool
    {
        $this->connect();

        return 1 === $this->redis->exists($key);
    }

    public function get(string $key): ?Game
    {
        $this->connect();

        $json = $this->redis->get($key);
        if (false === $json) {
            return null;
        }
        $data = JsonHelper::toArray($json);

        return Game::jsonUnserialize($data);
    }

    public function set(string $key, Game $game): void
    {
        $this->connect();

        $data = json_encode($game->jsonSerialize())."\n";
        if (false === $this->redis->set($key, $data)) {
            throw new RuntimeException('Cannot save the game: '.$key, 400);
        }
    }

    public function del(string $key): void
    {
        $this->connect();

        $this->redis->del($key);
    }

    private function connect(): void
    {
        try {
            if (!$this->redis->isConnected()) {
                if (false === $this->redis->connect($this->host)) {
                    throw new RuntimeException('Cannot connect to redis server: '.$this->host, 400);
                }
            }
        } catch (\Throwable $th) {
            throw new RuntimeException('Cannot connect to redis server: '.$this->host, 400, $th);
        }
    }
}
