<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\Tests\TestCase;
use RuntimeException;
use Taler\Taler as SdkTaler;

class TalerManagerTest extends TestCase
{
    public function test_it_builds_the_orders_manager_lazily(): void
    {
        $factory = new class implements CreatesTalerClients {
            public function make(): SdkTaler
            {
                throw new RuntimeException('The SDK client should not be created yet.');
            }

            public function options(): array
            {
                return ['base_url' => 'https://merchant.example.test'];
            }
        };

        $manager = new TalerManager($factory);

        $orders = $manager->orders();

        $this->assertInstanceOf(OrdersManager::class, $orders);
        $this->assertSame($orders, $manager->orders());
        $this->assertSame(['base_url' => 'https://merchant.example.test'], $manager->options());
    }
}
