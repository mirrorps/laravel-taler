<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerClientFactory;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\Tests\TestCase;

class LaravelTalerServiceProviderTest extends TestCase
{
    public function test_it_registers_the_core_bindings(): void
    {
        $this->assertInstanceOf(TalerClientFactory::class, $this->app->make(CreatesTalerClients::class));
        $this->assertInstanceOf(TalerManager::class, $this->app->make(TalerManager::class));
        $this->assertInstanceOf(TalerManager::class, $this->app->make('taler'));
        $this->assertInstanceOf(BankAccountsManager::class, $this->app->make(BankAccountsManager::class));
        $this->assertInstanceOf(ConfigManager::class, $this->app->make(ConfigManager::class));
        $this->assertInstanceOf(OrdersManager::class, $this->app->make(OrdersManager::class));
        $this->assertInstanceOf(TwoFactorAuthManager::class, $this->app->make(TwoFactorAuthManager::class));
    }

    public function test_it_merges_the_package_configuration(): void
    {
        $this->assertSame('readonly', config('taler.scope'));
        $this->assertTrue(config('taler.wrap_response'));
        $this->assertNull(config('taler.token'));
        $this->assertNull(config('taler.username'));
    }
}
