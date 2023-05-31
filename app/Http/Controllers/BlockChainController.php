<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Block\BlockService;
use App\Services\Chain\ChainHydrator;
use App\Services\Chain\ChainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockChainController
{
    private ChainService $chainService;
    private BlockService $blockService;
    private ChainHydrator $chainHydrator;

    /**
     * @param ChainService $chainService
     * @param BlockService $blockService
     * @param ChainHydrator $chainHydrator
     */
    public function __construct(ChainService $chainService, BlockService $blockService, ChainHydrator $chainHydrator)
    {
        $this->chainService = $chainService;
        $this->blockService = $blockService;
        $this->chainHydrator = $chainHydrator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->input('data');

        $newBlock = $this->blockService->createNewBlock($data);
        $currentChain = $this->chainService->appendNewBlock($newBlock);
        $this->chainService->saveCurrentChain($currentChain);

        return response()->json(['success' => true], 201);
    }

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $currentChain = $this->chainService->getCurrentChain();
        $response = $this->chainHydrator->extract($currentChain);

        return response()->json(['chain' => $response]);
    }
}
