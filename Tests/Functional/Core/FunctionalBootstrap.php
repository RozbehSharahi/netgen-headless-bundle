<?php

namespace Rs\NetgenHeadlessBundle\Tests\Functional\Core;

use Exception;
use Rs\NetgenHeadlessBundle\Tests\TestHelper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

require './vendor/autoload.php';

/**
 * @internal
 */
class FunctionalBootstrap extends AbstractFunctionalTest
{
    protected string $doNotRecreateFile = __DIR__ . '/../../../fast-tests';

    public function __construct()
    {
        (new Dotenv())->bootEnv(__DIR__ . '/../.env');
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

        if (!file_exists($this->doNotRecreateFile)) {
            (new TestHelper())->createDatabase($app);
        }

        static::ensureKernelShutdown();
        static::$kernel = null;
        static::$booted = false;

        return $this;
    }
}

try {
    (new FunctionalBootstrap())->setupDatabase();
} catch (Exception $e) {
    die('Could not initialize Database for testing: ' . $e->getMessage());
}
