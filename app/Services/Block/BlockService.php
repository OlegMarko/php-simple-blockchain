<?php

declare(strict_types=1);

namespace App\Services\Block;

use App\Entity\Block;
use App\Services\Chain\ChainService;

class BlockService
{
    private ChainService $chainService;
    private BlockHasher $blockHasher;

    /**
     * @param ChainService $chainService
     * @param BlockHasher $blockHasher
     */
    public function __construct(ChainService $chainService, BlockHasher $blockHasher)
    {
        $this->chainService = $chainService;
        $this->blockHasher = $blockHasher;
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

        $hash = $this->blockHasher->calculateHash($block);
        $block->setHash($hash);

        return $block;
    }
}
