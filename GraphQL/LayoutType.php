<?php


namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Values\Layout\Layout;
use Netgen\Layouts\Transfer\Output\OutputVisitor;

class LayoutType extends ObjectType
{

    public function __construct(OutputVisitor $outputVisitor, ZoneType $zoneType)
    {
        parent::__construct([
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => fn(Layout $layout) => (string)$layout->getId(),
                ],
                'zones' => [
                    'type' => Type::listOf($zoneType),
                    'resolve' => fn(Layout $layout) => $layout->getZones(),
                ],
                'json' => [
                    'type' => Type::string(),
                    'resolve' => fn(Layout $layout) => json_encode(
                        $outputVisitor->visit($layout)
                    )
                ]
            ]
        ]);
    }
}
