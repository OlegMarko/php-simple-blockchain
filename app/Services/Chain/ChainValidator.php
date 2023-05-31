<?php

declare(strict_types=1);

namespace App\Services\Chain;

use App\Entity\Chain;
use App\Services\Block\BlockValidator;

class ChainValidator
{
    private BlockValidator $blockValidator;

    /**
     * @param BlockValidator $blockValidator
     */
    public function __construct(BlockValidator $blockValidator)
    {
        $this->blockValidator = $blockValidator;
    }

    /**
     * @param Chain $chain
     * @return bool
     */
    public function validate(Chain $chain): bool
    {
        $blocks = $chain->get();
        $len = count($blocks);

        for($i = 1; $i < $len; $i++) {
            if (!$this->blockValidator->validate($blocks[$i - 1], $blocks[$i])) {
                return false;
            }
        }

        return true;
    }
}
