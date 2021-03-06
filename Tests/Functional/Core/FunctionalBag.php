<?php

namespace Rs\NetgenHeadlessBundle\Tests\Functional\Core;

use Exception;
use Netgen\Bundle\LayoutsAdminBundle\Controller\API\Block\Utils\CreateStructBuilder;
use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Service\LayoutResolverService;
use Netgen\Layouts\API\Service\LayoutService;
use Netgen\Layouts\API\Values\Layout\Layout;
use Netgen\Layouts\API\Values\Layout\LayoutCreateStruct;
use Netgen\Layouts\Block\Registry\BlockTypeRegistry;
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

    public function getLayoutResolverService(): LayoutResolverService
    {
        return $this->container->get('netgen_layouts.api.service.layout_resolver');
    }

    /**
     * @param string $query
     * @return array
     * @throws Exception
     */
    public function graphqlRequest(string $query): array
    {
        $this->getClient()->request('POST', '/graphql/netgen', [
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

    public function getBlockTypeRegistry(): BlockTypeRegistry
    {
        return $this->container->get('netgen_layouts.block.registry.block_type');
    }

    public function getCreateStructBuilder(): CreateStructBuilder
    {
        return $this->container->get('netgen_layouts.controller.api.block.create_struct_builder');
    }

    public function getBlockService(): BlockService
    {
        return $this->container->get(BlockService::class);
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

    /**
     * @param Layout $layout
     * @param string $zone
     * @param string $type
     * @return Layout
     * @throws BadStateException
     */
    public function addBlockToLayout(Layout $layout, string $zone, string $type): Layout
    {
        $blockType = $this->getBlockTypeRegistry()->getBlockType($type);
        $block = $this->getCreateStructBuilder()->buildCreateStruct($blockType);
        $layout = $this->getLayoutService()->createDraft($layout);
        $this->getBlockService()->createBlockInZone($block, $layout->getZone($zone));
        return $this->getLayoutService()->publishLayout($layout);
    }

}
