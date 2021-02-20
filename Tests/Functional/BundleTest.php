<?php

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;

class BundleTest extends AbstractFunctionalTest
{

    /**
     * @throws Exception
     */
    public function testBundleInstalled()
    {
        $client = static::createClient();
        $client->request('GET', '/netgen-headless/');
        self::assertEquals(HomeController::SUCCESS_MESSAGE, $client->getResponse()->getContent());
    }

}
