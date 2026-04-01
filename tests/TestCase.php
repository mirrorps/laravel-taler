<?php

namespace Mirrorps\LaravelTaler\Tests;

use Mirrorps\LaravelTaler\Facades\Taler;
use Mirrorps\LaravelTaler\LaravelTalerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelTalerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Taler' => Taler::class,
        ];
    }
}
