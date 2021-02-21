<?php

namespace Rs\NetgenHeadless\Tests;

use Exception;
use Rs\NetgenHeadless\Tests\Functional\AbstractFunctionalTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;

require './vendor/autoload.php';

/**
 * @internal
 */
class Bootstrap extends AbstractFunctionalTest
{
    public function __construct()
    {
        parent::__construct(null, [], '');
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function setupDatabase(): self
    {
        static::createClient();

        $app = new Application(self::$kernel);

        (new TestHelper())->createDatabase($app);
        
        static::ensureKernelShutdown();
        static::$kernel = null;
        static::$booted = false;

        return $this;
    }
}

try {
    (new Bootstrap())->setupDatabase();
} catch (Exception $e) {
    die('Could not initialize Database for testing: ' . $e->getMessage());
}
