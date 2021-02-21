<?php


namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class LayoutType extends ObjectType
{
    use TypeTrait;

    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => function () {
                        return null;
                    }
                ],
            ]
        ]);
    }
}
