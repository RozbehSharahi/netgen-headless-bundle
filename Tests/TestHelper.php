<?php

namespace Rs\NetgenHeadless\Tests;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class TestHelper
{

    /**
     * @param Application $application
     * @return $this
     * @throws Exception
     */
    public function createDatabase(Application $application): self
    {
        $autoExitState = $application->isAutoExitEnabled();

        $application->setAutoExit(false);
        $application->run(new ArrayInput([
            'doctrine:database:drop',
            '--no-interaction' => true,
            '--force' => true,
            '--if-exists' => true
        ]));
        $application->run(new ArrayInput([
            'doctrine:database:create',
            '--no-interaction' => true
        ]));
        $application->run(new ArrayInput([
            'doctrine:migrations:migrate',
            '--configuration' => __DIR__.'/../vendor/netgen/layouts-core/migrations/doctrine.yaml',
            '--no-interaction' => true
        ]));

        $application->setAutoExit($autoExitState);

        return $this;
    }

    /**
     * @param Application $application
     * @return $this
     * @throws Exception
     */
    public function createNetgenAssets(Application $application): self
    {
        $autoExitState = $application->isAutoExitEnabled();

        $application->run(new ArrayInput([
            'assets:install',
            '--symlink' => true,
            '--relative' => true
        ]));

        $application->setAutoExit($autoExitState);

        return $this;
    }

}
