<?php

declare(strict_types=1);

namespace App\Services\Chain\Storage;

use App\Entity\Chain;

class FileStorage implements Storage
{
    private string $pathToFile;

    public function __construct()
    {
        $this->pathToFile = base_path(env('BLOCKCHAIN_FILE_PATH'));
    }

    /**
     * @param Chain $chain
     * @return void
     */
    public function save(Chain $chain): void
    {
        $serializedChain = serialize($chain);
        $this->createIfNotExistBlockchainFile();
        $isOk = file_put_contents($this->pathToFile, $serializedChain, LOCK_EX);

        if (!$isOk) {
            throw new \RuntimeException('Error writing to file.');
        }
    }

    /**
     * @return Chain
     */
    public function get(): Chain
    {
        $serializedChain = file_get_contents($this->pathToFile);

        if ($serializedChain === false || $serializedChain === 0) {
            throw new \RuntimeException('Chain extraction error.');
        }

        return unserialize($serializedChain, [Chain::class]);
    }

    /**
     * @return void
     */
    private function createIfNotExistBlockchainFile(): void
    {
        $pathToFile = base_path(env('BLOCKCHAIN_FILE_PATH'));

        if (file_exists($pathToFile)) {
            return;
        }

        $result = file_put_contents($pathToFile, '', LOCK_EX);

        if ($result === false) {
            throw new \RuntimeException('Error creating file.');
        }
    }
}
