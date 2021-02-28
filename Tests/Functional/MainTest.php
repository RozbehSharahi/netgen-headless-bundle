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
            $response = $this->graphqlRequest($bag->getClient(), '
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

            $response = $this->graphqlRequest($bag->getClient(), '
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

    public function testCanGetJson()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->createPublishedLayout('Some name');

            $response = $this->graphqlRequest($bag->getClient(), '
                {
                    layouts {
                      json
                    }
                }
            ');

            self::assertKeyExistsInArray('data.layouts.0.json', $response);
            self::assertIsString($response['data']['layouts']['0']['json']);
            self::assertStringContainsString('Some name', $response['data']['layouts']['0']['json']);
        });
    }

}
