<?php

namespace Rs\NetgenHeadlessBundle\GraphQL;

use GraphQL\Type\Definition\InputObjectType;

class LayoutSearchInputType extends InputObjectType
{
    public function __construct(RequestInputType $requestInputType)
    {
        parent::__construct([
            'fields' => [
                'request' => $requestInputType,
            ]
        ]);
    }
}
