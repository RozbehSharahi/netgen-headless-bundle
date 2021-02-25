<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Layout\Zone;

class ZoneType extends ObjectType
{
    private BlockService $blockService;

    public function __construct(BlockService $blockService, BlockType $blockType)
    {
        $this->blockService = $blockService;

        parent::__construct([
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => fn(Zone $zone) => (string)$zone->getIdentifier(),
                ],
                'blocks' => [
                    'type' => Type::listOf($blockType),
                    'resolve' => fn(Zone $zone) => $blockService->loadZoneBlocks($zone)
                ]
            ]
        ]);
    }
}
