<?php


namespace Rs\NetgenHeadless\Tests\Functional;


use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Rs\NetgenHeadless\Tests\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return new TestKernel('test', false);
    }

    protected function withinTransaction($callback): self
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Did not pass callable to ' . __METHOD__);
        }

        $client = static::createClient();
        $container = static::$kernel->getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction();

        $callback($client, self::$kernel, $container);

        $entityManager->rollback();

        return $this;
    }
}
