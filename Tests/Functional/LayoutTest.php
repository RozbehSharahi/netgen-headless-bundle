<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadlessBundle\Tests\Functional;

use Ramsey\Uuid\Uuid;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\FunctionalBag;

class LayoutTest extends AbstractFunctionalTest
{

    public function testCanGetLayoutViaGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $layout = $bag->createPublishedLayout('Some name');

            $response = $bag->graphqlRequest('
                {
                    layouts {
                      id
                    }
                }
            ');

            self::assertKeyExistsInArray('data.layouts', $response);
            self::assertCount(1, $response['data']['layouts']);
            self::assertEquals($layout->getId(), $response['data']['layouts'][0]['id']);
        });
    }

    public function testErrorOnBadLayoutSearchInput()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $bag->graphqlRequest('
                {
                    layout(search: {  }) {
                      id
                    }
                }
            ');

            self::assertKeyContainsStringInArray(
                'errors.0.message', 'provide at least one of following fields on "search":', $response
            );
        });
    }

    public function testThrowsExceptionIfNotSearchGivenOnLayoutQuery()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $bag->graphqlRequest('
                {
                    layout {
                      id
                    }
                }
            ');

            self::assertKeyContainsStringInArray('errors.0.message', '"LayoutSearchInput!" is required', $response);
        });
    }

    public function testCanFindLayout()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $layout = $bag->createPublishedLayout('Some name');
            $layoutId = $layout->getId()->toString();

            $ruleCreateStruct = $bag->getLayoutResolverService()->newRuleCreateStruct();
            $ruleCreateStruct->layoutId = Uuid::fromString($layoutId);
            $rule = $bag->getLayoutResolverService()->createRule(
                $ruleCreateStruct
            );

            $targetCreateStruct = $bag->getLayoutResolverService()->newTargetCreateStruct('request_uri');
            $targetCreateStruct->value = '/hello-world';
            $bag->getLayoutResolverService()->addTarget($rule, $targetCreateStruct);

            $bag->getLayoutResolverService()->publishRule($rule);

            $response = $bag->graphqlRequest('
                {
                    layout(search: { request: { uri: "/hello-world?" } }) {
                      id
                    }
                }
            ');

            self::assertKeyEqualsInArray('data.layout.id', $layoutId, $response);
        });
    }

    public function testCanGetLayoutJson()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->createPublishedLayout('Some name');

            $response = $bag->graphqlRequest('
                {
                    layouts {
                      json
                    }
                }
            ');

            self::assertKeyExistsInArray('data.layouts.0.json', $response);
            self::assertKeyContainsStringInArray('data.layouts.0.json', 'Some name', $response);
        });
    }

    public function testCanGetLayoutZonesJson()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->createPublishedLayout('Some name');

            $response = $bag->graphqlRequest('
                {
                    layouts {
                      zones {
                        json
                      }
                    }
                }
            ');

            self::assertKeyExistsInArray('data.layouts.0.zones.0.json', $response);
            self::assertKeyContainsStringInArray('data.layouts.0.zones.0.json', 'main', $response);
        });
    }

}
