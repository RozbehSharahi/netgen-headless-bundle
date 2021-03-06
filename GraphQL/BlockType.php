<?php

namespace Rs\NetgenHeadlessBundle\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Transfer\Output\OutputVisitor;

class BlockType extends ObjectType
{
    public function __construct(OutputVisitor $outputVisitor)
    {
        parent::__construct([
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => fn(Block $block) => $block->getId()->toString()
                ],
                'json' => [
                    'type' => Type::string(),
                    'resolve' => fn(Block $block) => json_encode(
                        $outputVisitor->visit($block)
                    )
                ]
            ],
        ]);
    }
}
