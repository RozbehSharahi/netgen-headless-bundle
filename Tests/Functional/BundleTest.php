<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;
use Rs\NetgenHeadless\GraphQL\RootQueryType;
use Rs\NetgenHeadless\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadless\Tests\Functional\Core\FunctionalBag;

class BundleTest extends AbstractFunctionalTest
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

    public function testRootQueryTypeAliasSet()
    {
        self::assertContains('RootQuery', RootQueryType::getAliases());
    }

    public function testCanAccessGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $bag->graphqlRequest('
                {
                  netgenHeadlessSayHello
                }
            ');
            self::assertEquals('Hello', $response['data']['netgenHeadlessSayHello']);
        });
    }

}
