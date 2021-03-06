<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadlessBundle\Tests\Functional;

use Exception;
use Rs\NetgenHeadlessBundle\Controller\HomeController;
use Rs\NetgenHeadlessBundle\GraphQL\RootQueryType;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\FunctionalBag;

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
