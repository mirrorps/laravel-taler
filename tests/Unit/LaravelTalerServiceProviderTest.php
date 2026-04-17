<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\DonauCharity\DonauCharityManager;
use Mirrorps\LaravelTaler\Instance\InstanceManager;
use Mirrorps\LaravelTaler\Inventory\InventoryManager;
use Mirrorps\LaravelTaler\Logging\LogChannelResolver;
use Mirrorps\LaravelTaler\OtpDevices\OtpDevicesManager;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerClientFactory;
use Mirrorps\LaravelTaler\Templates\TemplatesManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\Wallet\WalletManager;
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
        $this->assertInstanceOf(InstanceManager::class, $this->app->make(InstanceManager::class));
        $this->assertInstanceOf(InventoryManager::class, $this->app->make(InventoryManager::class));
        $this->assertInstanceOf(OrdersManager::class, $this->app->make(OrdersManager::class));
        $this->assertInstanceOf(TwoFactorAuthManager::class, $this->app->make(TwoFactorAuthManager::class));
        $this->assertInstanceOf(OtpDevicesManager::class, $this->app->make(OtpDevicesManager::class));
        $this->assertInstanceOf(TemplatesManager::class, $this->app->make(TemplatesManager::class));
        $this->assertInstanceOf(WalletManager::class, $this->app->make(WalletManager::class));
        $this->assertInstanceOf(DonauCharityManager::class, $this->app->make(DonauCharityManager::class));
        $this->assertInstanceOf(LogChannelResolver::class, $this->app->make(LogChannelResolver::class));
    }

    public function test_it_binds_the_log_channel_resolver_as_a_singleton(): void
    {
        $first = $this->app->make(LogChannelResolver::class);
        $second = $this->app->make(LogChannelResolver::class);

        $this->assertSame($first, $second);
    }

    public function test_it_merges_the_package_configuration(): void
    {
        $this->assertSame('readonly', config('taler.scope'));
        $this->assertTrue(config('taler.wrap_response'));
        $this->assertFalse(config('taler.debug_logging_enabled'));
        $this->assertNull(config('taler.token'));
        $this->assertNull(config('taler.username'));
        $this->assertTrue(config('taler.logging_enabled'));
        $this->assertNull(config('taler.log_channel'));
    }
}
