<?php


namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Values\Layout\Layout;

class LayoutType extends ObjectType
{
    use TypeTrait;

    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => fn(Layout $layout) => (string)$layout->getId()
                ],
            ]
        ]);
    }
}
