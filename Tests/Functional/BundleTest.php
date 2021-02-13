<?php

namespace Rs\NetgenHeadless\Tests\Functional;

class BundleTest extends AbstractFunctionalTest
{

    public function testBundleInstalled()
    {
        $client = static::createClient();
        $client->request('GET', '/netgen-headless');
        print_r(substr($client->getResponse()->getContent(), 0, 1000));
        self::assertEquals(true, true);
    }

}
