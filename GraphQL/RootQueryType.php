<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Service\LayoutService;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;

class RootQueryType extends ObjectType implements AliasedInterface
{

    private LayoutService $layoutService;

    public function __construct(LayoutService $layoutService)
    {
        $this->layoutService = $layoutService;
        parent::__construct($this->getConfig());
    }

    public static function getAliases(): array
    {
        return ['RootQuery'];
    }

    protected function getConfig(): array
    {
        return [
            'fields' => [
                'netgenHeadlessSayHello' => [
                    'type' => Type::string(),
                    'resolve' => fn () => $this->netgenHeadlessSayHello()
                ],
                'layouts' => [
                    'type' => Type::listOf(LayoutType::instance()),
                    'resolve' => fn() => $this->layouts()
                ]
            ]
        ];
    }

    public function netgenHeadlessSayHello(): string
    {
        return 'Hello. I am There.';
    }

    private function layouts(): array
    {
        return $this->layoutService->loadLayouts()->getValues();
    }
}
