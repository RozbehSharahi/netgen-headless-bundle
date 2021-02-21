<?php

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;
use Rs\NetgenHeadless\Tests\Functional\Core\AbstractFunctionalTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class MainTest extends AbstractFunctionalTest
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

    public function testCanGetLayoutViaGraphQl()
    {
        $this->withinTransaction(function (KernelBrowser $client) {

        });
    }

}
