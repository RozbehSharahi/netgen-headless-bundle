<?php

namespace Rs\NetgenHeadless\Tests\Functional\Core;

use Exception;
use Netgen\Layouts\API\Service\LayoutService;
use Netgen\Layouts\API\Values\Layout\Layout;
use Netgen\Layouts\API\Values\Layout\LayoutCreateStruct;
use Netgen\Layouts\Exception\BadStateException;
use Netgen\Layouts\Layout\Registry\LayoutTypeRegistry;
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

    public function getLayoutService(): LayoutService
    {
        return $this->container->get('netgen_layouts.api.service.layout');
    }

    /**
     * @param string $query
     * @return array
     * @throws Exception
     */
    public function graphqlRequest(string $query): array
    {
        $this->getClient()->request('POST', '/graphql/', [
            'query' => $query
        ]);

        $result = json_decode($this->getClient()->getResponse()->getContent(), true);

        if (!$result) {
            throw new Exception("Response on graphql request '${query}' failed");
        }

        return $result;
    }

    public function getLayoutTypeRegistry(): LayoutTypeRegistry
    {
        return $this->container->get('netgen_layouts.layout.registry.layout_type');
    }

    /**
     * @param string $title
     * @param string $type
     * @param string $local
     * @return Layout
     * @throws BadStateException
     */
    public function createPublishedLayout(string $title, string $type = 'layout_1', string $local = 'en'): Layout
    {
        return $this->getLayoutService()->publishLayout(
            $this->getLayoutService()->createLayout(
                $this->createLayoutCreateStruct($title, $type, $local)
            )
        );
    }

    /**
     * @param string $title
     * @param string $type
     * @param string $local
     * @return LayoutCreateStruct
     */
    protected function createLayoutCreateStruct(
        string $title,
        string $type = 'layout_1',
        string $local = 'en'
    ): LayoutCreateStruct {
        return $this->getLayoutService()->newLayoutCreateStruct(
            $this->getLayoutTypeRegistry()->getLayoutType($type),
            $title,
            $local
        );
    }

}
