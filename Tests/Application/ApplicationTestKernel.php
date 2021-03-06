<?php

namespace Rs\NetgenHeadlessBundle\Tests\Application;

use Rs\NetgenHeadlessBundle\Tests\TestKernel;

class ApplicationTestKernel extends TestKernel {

    public function getProjectDir(): string
    {
        return __DIR__;
    }

}
