<?php

declare(strict_types=1);

namespace Http;

use ReflectionMethod;

abstract class Controller
{
    protected function request(string $requestClass): Request
    {
        $hive = \Base::instance();

        if (! is_subclass_of($requestClass, Request::class)) {
            throw new \InvalidArgumentException(
                "$requestClass must extend " . Request::class
            );
        }

        return new $requestClass($hive);
    }
}
