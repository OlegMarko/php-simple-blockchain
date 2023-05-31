<?php

declare(strict_types=1);

namespace App\Console\Commands\Chain;

use App\Entity\Block;
use App\Entity\Chain;
use App\Services\Block\Hasher;
use App\Services\Chain\ChainService;
use Illuminate\Console\Command;

class BlockChainGeneratorCommand extends Command
{
    protected $signature = 'generate:blockchain_with_block';

    protected $description = 'Create new blockchain with genesis block';

    private ChainService $chainService;
    private Hasher $blockHasher;

    /**
     * @param ChainService $chainService
     * @param Hasher $blockHasher
     */
    public function __construct(ChainService $chainService, Hasher $blockHasher)
    {
        parent::__construct();

        $this->chainService = $chainService;
        $this->blockHasher = $blockHasher;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $initBlock = new Block(0, '', 'init block');

        $hash = $this->blockHasher->calculateHash($initBlock);
        $initBlock->setHash($hash);

        $chain = new Chain([$initBlock]);

        $this->chainService->saveCurrentChain($chain);
    }
}
