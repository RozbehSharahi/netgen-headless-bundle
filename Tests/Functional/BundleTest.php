<?php

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class BundleTest extends AbstractFunctionalTest
{

    /**
     * @throws Exception
     */
    public function testBundleInstalled()
    {
        $this->withinTransaction(function (KernelBrowser $client) {
            $client->request('GET', '/netgen-headless/');
            self::assertEquals(HomeController::SUCCESS_MESSAGE, $client->getResponse()->getContent());
        });
    }

}
