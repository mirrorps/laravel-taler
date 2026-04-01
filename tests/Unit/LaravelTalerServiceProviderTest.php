<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerClientFactory;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\Tests\TestCase;

class LaravelTalerServiceProviderTest extends TestCase
{
    public function test_it_registers_the_core_bindings(): void
    {
        $this->assertInstanceOf(TalerClientFactory::class, $this->app->make(CreatesTalerClients::class));
        $this->assertInstanceOf(TalerManager::class, $this->app->make(TalerManager::class));
        $this->assertInstanceOf(TalerManager::class, $this->app->make('taler'));
        $this->assertInstanceOf(OrdersManager::class, $this->app->make(OrdersManager::class));
    }

    public function test_it_merges_the_package_configuration(): void
    {
        $this->assertSame('readonly', config('taler.scope'));
        $this->assertTrue(config('taler.wrap_response'));
        $this->assertNull(config('taler.token'));
        $this->assertNull(config('taler.username'));
    }
}
