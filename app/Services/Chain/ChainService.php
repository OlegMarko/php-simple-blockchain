<?php

declare(strict_types=1);

namespace App\Services\Chain;

use App\Entity\Block;
use App\Entity\Chain;
use App\Services\Chain\Storage\Storage;

class ChainService
{
    private ChainValidator $chainValidator;
    private Storage $chainStorage;

    /**
     * @param ChainValidator $chainValidator
     * @param Storage $chainStorage
     */
    public function __construct(ChainValidator $chainValidator, Storage $chainStorage)
    {
        $this->chainValidator = $chainValidator;
        $this->chainStorage = $chainStorage;
    }

    /**
     * @param Block $newBlock
     * @return Chain
     */
    public function appendNewBlock(Block $newBlock): Chain
    {
        $currentChain = $this->getCurrentChain();

        $blocks = $currentChain->get();
        $blocks[] = $newBlock;

        return $currentChain->update($blocks);
    }

    /**
     * @return Chain
     */
    public function getCurrentChain(): Chain
    {
        return $this->chainStorage->get();
    }

    /**
     * @param Chain $chain
     * @return void
     */
    public function saveCurrentChain(Chain $chain): void
    {
        $isChainValid = $this->chainValidator->validate($chain);

        if (!$isChainValid) {
            throw new \RuntimeException('New chain is not valid.');
        }

        $this->chainStorage->save($chain);
    }

    /**
     * @return Block
     */
    public function getLastBlock(): Block
    {
        $blocks = $this->getCurrentChain()->get();

        return end($blocks);
    }
}
