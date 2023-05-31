<?php

declare(strict_types=1);

namespace App\Services\Block;

use App\Entity\Block;
use App\Services\Chain\ChainService;

class BlockService
{
    private ChainService $chainService;
    private Hasher $hasher;

    /**
     * @param ChainService $chainService
     * @param Hasher $hasher
     */
    public function __construct(ChainService $chainService, Hasher $hasher)
    {
        $this->chainService = $chainService;
        $this->hasher = $hasher;
    }

    /**
     * @param string $data
     * @return Block
     */
    public function createNewBlock(string $data): Block
    {
        $prevBlock = $this->chainService->getLastBlock();

        $index = $prevBlock->getIndex() + 1;
        $prevHash = $prevBlock->getHash();

        $block = new Block($index, $prevHash, $data);

        $hash = $this->hasher->calculateHash($block);
        $block->setHash($hash);

        return $block;
    }
}
