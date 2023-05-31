<?php

declare(strict_types=1);

namespace App\Services\Block;

use App\Entity\Block;

class BlockHydrator
{
    /**
     * @param Block $block
     * @return array
     */
    public function extract(Block $block): array
    {
        return [
            'index' => $block->getIndex(),
            'hash' => $block->getHash(),
            'prevHash' => $block->getPreviousHash(),
            'timestamp' => $block->getTimestamp(),
            'data' => $block->getData(),
        ];
    }
}
