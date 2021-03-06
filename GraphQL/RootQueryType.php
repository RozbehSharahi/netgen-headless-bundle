<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Server\RequestError;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Netgen\Layouts\API\Service\LayoutService;
use Netgen\Layouts\API\Values\Layout\Layout;
use Netgen\Layouts\Layout\Resolver\LayoutResolver;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Symfony\Component\HttpFoundation\Request;

class RootQueryType extends ObjectType implements AliasedInterface
{

    const SEARCH_ONE_OF_MANDATORY_FIELDS = [
        'request'
    ];

    public function __construct(
        LayoutService $layoutService,
        LayoutResolver $layoutResolver,
        LayoutType $layoutType,
        LayoutSearchInputType $layoutSearchInputType
    ) {
        parent::__construct([
            'fields' => [
                'netgenHeadlessSayHello' => [
                    'type' => Type::string(),
                    'resolve' => fn() => 'Hello'
                ],
                'layout' => [
                    'type' => $layoutType,
                    'args' => [
                        'search' => Type::nonNull($layoutSearchInputType)
                    ],
                    'resolve' => fn($unused, array $input) => $this->resolveLayout($layoutResolver, $input)
                ],
                'layouts' => [
                    'type' => Type::listOf($layoutType),
                    'resolve' => fn() => $layoutService->loadLayouts()->getValues()
                ]
            ]
        ]);
    }

    /**
     * @param LayoutResolver $layoutResolver
     * @param array $input
     * @return Layout|null
     * @throws RequestError
     */
    private function resolveLayout(LayoutResolver $layoutResolver, array $input): ?Layout
    {
        if (empty($input['search']['request'])) {
            throw new RequestError(
                'You have to provide at least one of following fields on "search": ' .
                implode(' or ', self::SEARCH_ONE_OF_MANDATORY_FIELDS)
            );
        }

        $rule = $layoutResolver->resolveRule($this->getRequestByUri($input['search']['request']['uri']));
        return $rule ? $rule->getLayout() : null;
    }

    private function getRequestByUri(string $uri): Request
    {
        $parts = explode('?', $uri);
        $uri = $parts[0];
        parse_str($parts[1] ?? '', $parameters);

        return Request::create($uri, Request::METHOD_GET, $parameters);
    }

    public static function getAliases(): array
    {
        return ['RootQuery'];
    }

}
