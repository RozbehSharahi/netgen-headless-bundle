<?php

use Rs\NetgenHeadless\Tests\Functional\AbstractFunctionalTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

require './vendor/autoload.php';

/**
 * @internal
 */
class FunctionalBootstrap extends AbstractFunctionalTest
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
        $app->setAutoExit(false);
        $app->run(new ArrayInput(['doctrine:database:drop', '--no-interaction' => true, '--force' => true]));
        $app->run(new ArrayInput(['doctrine:database:create', '--no-interaction' => true]));
        $app->run(new ArrayInput([
            'doctrine:migrations:migrate',
            '--configuration' => 'vendor/netgen/layouts-core/migrations/doctrine.yaml',
            '--no-interaction' => true
        ]));
        
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
