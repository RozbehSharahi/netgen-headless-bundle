<?php

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Rs\NetgenHeadless\Controller\HomeController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class BundleTest extends AbstractFunctionalTest
{

    /**
     * @throws Exception
     */
    public function testBundleInstalled()
    {
        $client = static::createClient();

        $app = new Application(self::$kernel);
        $app->setAutoExit(false);
        $app->run(new ArrayInput(['doctrine:schema:drop', '--force' => true]));
        $app->run(new ArrayInput(['doctrine:schema:create']));

        $client->request('GET', '/netgen-headless/');
        self::assertEquals(HomeController::SUCCESS_MESSAGE, $client->getResponse()->getContent());
    }

}
