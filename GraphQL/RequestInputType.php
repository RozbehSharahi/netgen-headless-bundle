<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class RequestInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'uri' => Type::nonNull(Type::string())
            ]
        ]);
    }
}
