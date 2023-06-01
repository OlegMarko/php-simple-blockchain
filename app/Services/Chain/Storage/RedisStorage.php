<?php

declare(strict_types=1);

namespace App\Services\Chain\Storage;

use App\Entity\Chain;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RedisStorage implements Storage
{
    private string $chainRedisKey;

    public function __construct()
    {
        $this->chainRedisKey = base_path(env('BLOCKCHAIN_REDIS_KEY'));
    }

    /**
     * @param Chain $chain
     * @return void
     */
    public function save(Chain $chain): void
    {
        $serializedChain = serialize($chain);
        $isOk = app('redis')->set($this->chainRedisKey, $serializedChain);

        if (!$isOk) {
            throw new \RuntimeException('Error writing to Redis.');
        }
    }

    /**
     * @return Chain
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(): Chain
    {
        $serializedChain = app('redis')->get($this->chainRedisKey);

        if ($serializedChain === false || $serializedChain === null) {
            throw new \RuntimeException('Chain extraction error.');
        }

        return unserialize($serializedChain, [Chain::class]);
    }
}
