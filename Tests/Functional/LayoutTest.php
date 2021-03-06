<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadless\Tests\Functional;

use Rs\NetgenHeadless\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadless\Tests\Functional\Core\FunctionalBag;

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
