<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;
use Rs\NetgenHeadless\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadless\Tests\Functional\Core\FunctionalBag;

class MainTest extends AbstractFunctionalTest
{

    /**
     * @throws Exception
     */
    public function testBundleInstalled()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->getClient()->request('GET', '/netgen-headless/');
            self::assertEquals(HomeController::SUCCESS_MESSAGE, $bag->getClient()->getResponse()->getContent());
        });
    }

    public function testCanAccessGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $bag->graphqlRequest('
                {
                  netgenHeadlessSayHello
                }
            ');
            self::assertEquals('Hello. I am There.', $response['data']['netgenHeadlessSayHello']);
        });
    }

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
            self::assertIsString($response['data']['layouts'][0]['json']);
            self::assertStringContainsString('Some name', $response['data']['layouts'][0]['json']);
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
            self::assertStringContainsString('main', $response['data']['layouts'][0]['zones'][0]['json']);
        });
    }

}
