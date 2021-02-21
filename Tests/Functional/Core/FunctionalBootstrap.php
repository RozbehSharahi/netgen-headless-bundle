<?php

namespace Rs\NetgenHeadless\Tests\Functional\Core;

use Exception;
use Rs\NetgenHeadless\Tests\TestHelper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

require './vendor/autoload.php';

/**
 * @internal
 */
class FunctionalBootstrap extends AbstractFunctionalTest
{
    protected string $doNotRecreateFile = __DIR__ . '/do-not-recreate-database';

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

        file_put_contents($this->doNotRecreateFile, '');

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
