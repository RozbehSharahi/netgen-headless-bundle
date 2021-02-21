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
                'netgenHeadlessSayHello' => [
                    'type' => Type::string(),
                    'resolve' => fn () => $this->netgenHeadlessSayHello()
                ]
            ]
        ];
    }

    public function netgenHeadlessSayHello(): string
    {
        return 'Hello. I am There.';
    }

    public static function getAliases(): array
    {
        return ['RootQuery'];
    }
}
