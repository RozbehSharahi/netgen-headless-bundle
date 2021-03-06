<?php

namespace Rs\NetgenHeadless\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * @codeCoverageIgnore
 */
class NetgenHeadlessExtension extends Extension implements PrependExtensionInterface
{

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return $this
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): self
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        return $this;
    }


    public function prepend(ContainerBuilder $container): self
    {
        $yaml = file_get_contents(__DIR__ . '/../Resources/config/overblog_graphql.yaml');
        $yaml = preg_replace('/%bundle\.dir%/', __DIR__ . '/..', $yaml);
        $config = Yaml::parse($yaml);

        $container->prependExtensionConfig('overblog_graphql', $config['overblog_graphql']);

        return $this;
    }
}
