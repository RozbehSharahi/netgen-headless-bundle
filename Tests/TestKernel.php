<?php

namespace Rs\NetgenHeadless\Tests;

use Exception;
use Overblog\GraphQLBundle\OverblogGraphQLBundle;
use Rs\NetgenHeadless\NetgenHeadlessBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new MonologBundle(),
            new OverblogGraphQLBundle(),
            new NetgenHeadlessBundle()
        ];
    }

    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/Resources/config.yaml');
    }
}
