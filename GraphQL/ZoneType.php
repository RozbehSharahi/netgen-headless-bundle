<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Layout\Zone;
use Netgen\Layouts\Transfer\Output\OutputVisitor;

class ZoneType extends ObjectType
{
    private BlockService $blockService;

    public function __construct(OutputVisitor $outputVisitor, BlockService $blockService, BlockType $blockType)
    {
        $this->blockService = $blockService;

        parent::__construct([
            'fields' => [
                'identifier' => [
                    'type' => Type::string(),
                    'resolve' => fn(Zone $zone) => $zone->getIdentifier(),
                ],
                'blocks' => [
                    'type' => Type::listOf($blockType),
                    'resolve' => fn(Zone $zone) => $blockService->loadZoneBlocks($zone)
                ],
                'json' => [
                    'type' => Type::string(),
                    'resolve' => fn(Zone $zone) => json_encode(
                        $outputVisitor->visit($zone)
                    )
                ]
            ]
        ]);
    }
}
