<?php


namespace Rs\NetgenHeadlessBundle\Tests\Functional;


use Exception;
use Netgen\Layouts\Exception\BadStateException;
use Rs\NetgenHeadlessBundle\Tests\Functional\Core\FunctionalBag;

class BlocksTest extends Core\AbstractFunctionalTest
{

    /**
     * @throws BadStateException
     * @throws Exception
     */
    public function testCanGetZones()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $layout = $bag->createPublishedLayout('Some layout');

            $bag->addBlockToLayout($layout, 'main', 'text');

            $response = $bag->graphqlRequest('
               {
                 layouts {
                   zones {
                     identifier
                     blocks {
                       id
                       json
                     }
                   }
                 }
               }
            ');

            self::assertKeyExistsInArray('data.layouts.0.zones.0.blocks.0.json', $response);
            self::assertKeyEqualsInArray('data.layouts.0.zones.0.identifier', 'main', $response);
            self::assertKeyContainsStringInArray('data.layouts.0.zones.0.blocks.0.json', 'text', $response);
        });
    }
}
