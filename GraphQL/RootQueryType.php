<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Service\LayoutService;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;

class RootQueryType extends ObjectType implements AliasedInterface
{

    public function __construct(LayoutService $layoutService, LayoutType $layoutType)
    {
        parent::__construct([
            'fields' => [
                'netgenHeadlessSayHello' => [
                    'type' => Type::string(),
                    'resolve' => fn() => $this->netgenHeadlessSayHello()
                ],
                'layouts' => [
                    'type' => Type::listOf($layoutType),
                    'resolve' => fn() => $layoutService->loadLayouts()->getValues()
                ]
            ]
        ]);
    }

    public static function getAliases(): array
    {
        return ['RootQuery'];
    }

    public function netgenHeadlessSayHello(): string
    {
        return 'Hello. I am There.';
    }

}
