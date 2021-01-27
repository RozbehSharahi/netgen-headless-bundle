<?php

namespace Rs\NetgenHeadless\GraphQl;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;

class QueryType extends ObjectType implements AliasedInterface
{

    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'helloWorld' => [
                    'type' => Type::string(),
                    'resolve' => function () {
                        return 'hello';
                    }
                ]
            ]
        ]);
    }

    public static function getAliases(): array
    {
        return ['Query'];
    }
}
