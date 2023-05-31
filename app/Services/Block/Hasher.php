<?php

declare(strict_types=1);

namespace App\Services\Block;

use App\Entity\Block;

class Hasher
{
    /**
     * @param Block $block
     * @return string
     */
    public function calculateHash(Block $block): string
    {
        return hash(
            'sha256',
            $block->getIndex() . $block->getPreviousHash() . $block->getTimestamp() . $block->getData()
        );
    }
}
