<?php

namespace Rs\NetgenHeadless\Tests\Functional\Core;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;

class FunctionalBag
{

    private KernelBrowser $client;

    private KernelInterface $kernel;

    private ContainerInterface $container;

    public function __construct(KernelBrowser $client, KernelInterface $kernel, ContainerInterface $container)
    {
        $this->client = $client;
        $this->kernel = $kernel;
        $this->container = $container;
    }

    public function getClient(): KernelBrowser
    {
        return $this->client;
    }

    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

}
