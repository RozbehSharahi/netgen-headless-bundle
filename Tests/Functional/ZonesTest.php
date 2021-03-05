<?php


namespace Rs\NetgenHeadless\Tests\Functional;


use Exception;
use Netgen\Layouts\Exception\BadStateException;
use Rs\NetgenHeadless\Tests\Functional\Core\FunctionalBag;

class ZonesTest extends Core\AbstractFunctionalTest
{

    /**
     * @throws BadStateException
     * @throws Exception
     */
    public function testCanGetZones()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->createPublishedLayout('Some layout', 'layout_2');

            $response = $bag->graphqlRequest('
               {
                 layouts {
                   zones {
                     identifier
                   }
                 }
               }
            ');

            self::assertCount(5, $response['data']['layouts'][0]['zones']);
        });
    }
}
