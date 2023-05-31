<?php

declare(strict_types=1);

namespace App\Services\Chain;

use App\Entity\Chain;
use App\Services\Block\BlockHydrator;

class ChainHydrator
{
    private BlockHydrator $blockHydrator;

    /**
     * @param BlockHydrator $blockHydrator
     */
    public function __construct(BlockHydrator $blockHydrator)
    {
        $this->blockHydrator = $blockHydrator;
    }

    /**
     * @param Chain $chain
     * @return array
     */
    public function extract(Chain $chain): array
    {
        $result = [];
        $blocks = $chain->get();

        foreach ($blocks as $block) {
            $result[] = $this->blockHydrator->extract($block);
        }

        return $result;
    }
}
