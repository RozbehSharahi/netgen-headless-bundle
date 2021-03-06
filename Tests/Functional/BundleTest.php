<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadlessBundle\Tests\Functional;

use Rs\NetgenHeadlessBundle\GraphQL\NetgenQueryType;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\FunctionalBag;

class BundleTest extends AbstractFunctionalTest
{

    public function testRootQueryTypeAliasSet()
    {
        self::assertContains('NetgenQuery', NetgenQueryType::getAliases());
    }

    public function testCanAccessGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $bag->graphqlRequest('
                {
                  sayHello
                }
            ');
            self::assertEquals('Hello', $response['data']['sayHello']);
        });
    }

}
