<?php

namespace Rs\NetgenHeadlessBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Exception;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Netgen\Bundle\ContentBrowserBundle\NetgenContentBrowserBundle;
use Netgen\Bundle\ContentBrowserUIBundle\NetgenContentBrowserUIBundle;
use Netgen\Bundle\LayoutsAdminBundle\NetgenLayoutsAdminBundle;
use Netgen\Bundle\LayoutsBundle\NetgenLayoutsBundle;
use Netgen\Bundle\LayoutsDebugBundle\NetgenLayoutsDebugBundle;
use Netgen\Bundle\LayoutsStandardBundle\NetgenLayoutsStandardBundle;
use Netgen\Bundle\LayoutsUIBundle\NetgenLayoutsUIBundle;
use Overblog\GraphiQLBundle\OverblogGraphiQLBundle;
use Overblog\GraphQLBundle\OverblogGraphQLBundle;
use Rs\NetgenHeadlessBundle\NetgenHeadlessBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

class TestKernel extends Kernel
{

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new MonologBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new SensioFrameworkExtraBundle(),
            new TwigBundle(),
            new TwigExtraBundle(),
            new KnpMenuBundle(),
            new NetgenContentBrowserUIBundle(),
            new NetgenContentBrowserBundle(),
            new NetgenLayoutsUIBundle(),
            new NetgenLayoutsAdminBundle(),
            new NetgenLayoutsBundle(),
            new NetgenLayoutsDebugBundle(),
            new NetgenLayoutsStandardBundle(),
            new OverblogGraphQLBundle(),
            new OverblogGraphiQLBundle(),
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
