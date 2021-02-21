<?php

namespace Rs\NetgenHeadless\GraphQL;

use GraphQL\Type\Definition\ObjectType;

trait TypeTrait
{
    protected static ?self $instance = null;

    /**
     * @return self|ObjectType
     */
    public static function instance(): self
    {
        return self::$instance = self::$instance ?: new static();
    }
}
