<?php

namespace Tests;

use App\Entity\Block;
use App\Entity\Chain;
use App\Http\Controllers\BlockChainController;
use App\Services\Block\BlockService;
use App\Services\Chain\ChainHydrator;
use App\Services\Chain\ChainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;

class BlockChainControllerTest extends TestCase
{
    private ChainService|Mockery\MockInterface|Mockery\LegacyMockInterface $chainService;
    private BlockService|Mockery\LegacyMockInterface|Mockery\MockInterface $blockService;
    private ChainHydrator|Mockery\LegacyMockInterface|Mockery\MockInterface $chainHydrator;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->chainService = Mockery::mock(ChainService::class);
        $this->blockService = Mockery::mock(BlockService::class);
        $this->chainHydrator = Mockery::mock(ChainHydrator::class);
    }

    public function testCreate(): void
    {
        $controller = new BlockChainController(
            $this->chainService,
            $this->blockService,
            $this->chainHydrator
        );

        $data = 'Test data';
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('data')->andReturn($data);

        $newBlock = Mockery::mock(Block::class);
        $currentChain = Mockery::mock(Chain::class);

        $this->blockService->shouldReceive('createNewBlock')->once()->with($data)->andReturn($newBlock);
        $this->chainService->shouldReceive('appendNewBlock')->once()->with($newBlock)->andReturn($currentChain);
        $this->chainService->shouldReceive('saveCurrentChain')->once()->with($currentChain);

        $response = $controller->create($request);

        $expectedResponse = response()->json(['success' => true], 201);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());
    }

    public function testShow(): void
    {
        $controller = new BlockChainController(
            $this->chainService,
            $this->blockService,
            $this->chainHydrator
        );

        $currentChain = Mockery::mock(Chain::class);
        $response = ['Mocked response'];

        $this->chainService->shouldReceive('getCurrentChain')->once()->andReturn($currentChain);
        $this->chainHydrator->shouldReceive('extract')->once()->with($currentChain)->andReturn($response);

        $expectedResponse = response()->json(['chain' => $response]);
        $response = $controller->show();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());
    }
}
