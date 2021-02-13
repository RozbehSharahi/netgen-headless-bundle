<?php


namespace Rs\NetgenHeadless\Tests\Functional;


use Rs\NetgenHeadless\Tests\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractFunctionalTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return new TestKernel('test', false);
    }
}
