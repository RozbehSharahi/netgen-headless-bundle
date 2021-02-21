<?php

namespace Rs\NetgenHeadless\Tests\Functional\Core;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTest extends WebTestCase
{
    protected static function getKernelClass(): FunctionalTestKernel
    {
        return new FunctionalTestKernel('test', false);
    }

    /**
     * @param string $key
     * @param array $array
     * @param string $delimiter
     * @throws Exception
     */
    static protected function assertKeyExistsInArray(string $key, array $array, string $delimiter = '.'): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($array),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $path = [];
        $flatArray = [];

        foreach ($iterator as $k => $value) {
            $path[$iterator->getDepth()] = $k;
            $flatArray[implode($delimiter, array_slice($path, 0, $iterator->getDepth() + 1))] = $value;
        }

        if (!isset($flatArray[$key])) {
            throw new Exception("Failed asserting that key '${key}' exists in: " . print_r($array, true));
        }
    }

    protected function withinTransaction($callback): self
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Did not pass callable to ' . __METHOD__);
        }

        $client = static::createClient();

        static::$kernel->getContainer();

        $container = static::$container;

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction();

        $callback(new FunctionalBag($client, self::$kernel, $container));

        $entityManager->rollback();

        return $this;
    }

    /**
     * @param KernelBrowser $client
     * @param string $query
     * @return array
     * @throws Exception
     */
    protected function graphqlRequest(KernelBrowser $client, string $query): array
    {
        $client->request('POST', '/graphql/', [
            'query' => $query
        ]);

        $result = json_decode($client->getResponse()->getContent(), true);

        if (!$result) {
            throw new Exception("Response on graphql request '${query}' failed");
        }

        return $result;
    }
}
