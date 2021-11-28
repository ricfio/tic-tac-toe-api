<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter\Repository;

use App\Application\Helper\JsonHelper;
use App\Application\Repository\GameRepositoryInterface;
use App\Domain\Model\Game;
use RuntimeException;

class GameRepositoryFolder implements GameRepositoryInterface
{
    private string $basepath;

    public function __construct(string $basepath)
    {
        if (!file_exists($basepath)) {
            if (false === mkdir($basepath, 0777, true)) {
                throw new RuntimeException('Storage path not writeable: '.$basepath, 400);
            }
        }
        $this->basepath = realpath($basepath);
    }

    public function has(string $key): bool
    {
        $filename = $this->getFilename($key);

        return file_exists($filename);
    }

    public function get(string $key): ?Game
    {
        if (!$this->has($key)) {
            return null;
        }
        $filename = $this->getFilename($key);
        $json = file_get_contents($filename);
        $data = JsonHelper::toArray($json);

        return Game::jsonUnserialize($data);
    }

    public function set(string $key, Game $game): void
    {
        $filename = $this->getFilename($key);
        if (false === file_put_contents($filename, json_encode($game->jsonSerialize())."\n")) {
            throw new RuntimeException('Storage path not writeable: '.$filename, 400);
        }
    }

    public function del(string $key): void
    {
        $filename = $this->getFilename($key);
        unlink($filename);
    }

    private function getFilename(string $key): string
    {
        return sprintf('%s/%s.json', $this->basepath, $key);
    }
}
