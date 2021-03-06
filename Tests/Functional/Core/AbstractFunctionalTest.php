<?php

namespace Rs\NetgenHeadlessBundle\Tests\Functional\Core;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTest extends WebTestCase
{
    protected static function getKernelClass(): FunctionalTestKernel
    {
        return new FunctionalTestKernel('test', false);
    }

    /**
     * @param array $array
     * @param string $delimiter
     * @return array
     */
    static private function nestedArrayToFlatArray(array $array, string $delimiter = '.'): array
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

        return $flatArray;
    }

    /**
     * @param string $key
     * @param array $array
     * @param string $delimiter
     * @throws Exception
     */
    static protected function assertKeyExistsInArray(string $key, array $array, string $delimiter = '.'): void
    {
        self::assertArrayHasKey(
            $key,
            self::nestedArrayToFlatArray($array, $delimiter),
            "Failed asserting that key '${key}' exists in: " . print_r($array, true)
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param array $array
     * @param string $delimiter
     * @throws Exception
     */
    static protected function assertKeyEqualsInArray(
        string $key,
        $value,
        array $array,
        string $delimiter = '.'
    ): void {
        self::assertEquals(
            $value,
            self::nestedArrayToFlatArray($array, $delimiter)[$key],
            "Failed asserting that key '${key}' is equal to '" .
            print_r($value, true) . "' in: " . print_r($array, true)
        );
    }

    /**
     * @param string $key
     * @param string $value
     * @param array $array
     * @param string $delimiter
     * @throws Exception
     */
    static protected function assertKeyContainsStringInArray(
        string $key,
        string $value,
        array $array,
        string $delimiter = '.'
    ): void {
        $foundValue = self::nestedArrayToFlatArray($array, $delimiter)[$key] ?? null;

        if (!$foundValue || !is_string($foundValue)) {
            throw new Exception("Expected key '$key' to be string, but it was not");
        }

        self::assertStringContainsString(
            $value,
            $foundValue,
            "Failed asserting that key '${key}' contains '" .
            print_r($value, true) . "' in: " . print_r($array, true)
        );
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
}
