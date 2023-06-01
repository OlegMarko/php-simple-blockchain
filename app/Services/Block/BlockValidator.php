<?php

declare(strict_types=1);

namespace App\Services\Block;

use App\Entity\Block;

class BlockValidator
{
    private BlockHasher $blockHasher;

    /**
     * @param BlockHasher $blockHasher
     */
    public function __construct(BlockHasher $blockHasher)
    {
        $this->blockHasher = $blockHasher;
    }

    /**
     * @param Block $prevBlock
     * @param Block $newBlock
     * @return bool
     */
    public function validate(Block $prevBlock, Block $newBlock): bool
    {
        if ($prevBlock->getIndex() + 1 !== $newBlock->getIndex()) {
            return false;
        }

        if ($prevBlock->getHash() !== $newBlock->getPreviousHash()) {
            return false;
        }

        if ($this->blockHasher->calculateHash($newBlock) !== $newBlock->getHash()) {
            return false;
        }

        return true;
    }
}
