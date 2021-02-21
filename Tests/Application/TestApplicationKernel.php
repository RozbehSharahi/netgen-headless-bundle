<?php

namespace Rs\NetgenHeadless\Tests\Application;

use Rs\NetgenHeadless\Tests\TestKernel;

class TestApplicationKernel extends TestKernel {

    public function getProjectDir(): string
    {
        return __DIR__;
    }

}
