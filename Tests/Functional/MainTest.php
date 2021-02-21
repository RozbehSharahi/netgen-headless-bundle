<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Rs\NetgenHeadless\Tests\Functional;

use Exception;
use Netgen\Layouts\Core\Service\LayoutService;
use Netgen\Layouts\Layout\Registry\LayoutTypeRegistry;
use Rs\NetgenHeadless\Controller\HomeController;
use Rs\NetgenHeadless\Tests\Functional\Core\AbstractFunctionalTest;
use Rs\NetgenHeadless\Tests\Functional\Core\FunctionalBag;

class MainTest extends AbstractFunctionalTest
{

    /**
     * @throws Exception
     */
    public function testBundleInstalled()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $bag->getClient()->request('GET', '/netgen-headless/');
            self::assertEquals(HomeController::SUCCESS_MESSAGE, $bag->getClient()->getResponse()->getContent());
        });
    }

    public function testCanAccessGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            $response = $this->graphqlRequest($bag->getClient(), '
                {
                  netgenHeadlessSayHello
                }
            ');
            self::assertEquals('Hello. I am There.', $response['data']['netgenHeadlessSayHello']);
        });
    }

    public function testCanGetLayoutViaGraphQl()
    {
        $this->withinTransaction(function (FunctionalBag $bag) {
            /** @var LayoutService $layoutService */
            $layoutService = $bag->getContainer()->get('netgen_layouts.api.service.layout');

            /** @var LayoutTypeRegistry $layoutTypeRegistry */
            $layoutTypeRegistry = $bag->getContainer()->get('netgen_layouts.layout.registry.layout_type');

            $layout = $layoutService->createLayout(
                $layoutService->newLayoutCreateStruct(
                    $layoutTypeRegistry->getLayoutType('layout_1'),
                    'Test Layout',
                    'en'
                )
            );

            $layout = $layoutService->publishLayout($layout);

            self::assertTrue($layout->isPublished());

            $response = $this->graphqlRequest($bag->getClient(), '
                {
                    layouts {
                      id
                    }
                }
            ');

            self::assertKeyExistsInArray('data.layouts',$response);
        });
    }

}
