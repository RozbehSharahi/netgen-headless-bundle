<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;

class RootQueryType extends ObjectType implements AliasedInterface
{

    public function __construct()
    {
        parent::__construct($this->getConfig());
    }

    protected function getConfig(): array
    {
        return [
            'fields' => [
                'helloWorld' => [
                    'type' => Type::string(),
                    'resolve' => fn () => $this->helloWorld()
                ]
            ]
        ];
    }

    public function helloWorld(): string
    {
        return 'In bundle';
    }

    public static function getAliases(): array
    {
        return ['RootQuery'];
    }
}
