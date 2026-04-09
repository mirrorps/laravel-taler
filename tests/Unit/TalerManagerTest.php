<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\DonauCharity\DonauCharityManager;
use Mirrorps\LaravelTaler\Instance\InstanceManager;
use Mirrorps\LaravelTaler\Inventory\InventoryManager;
use Mirrorps\LaravelTaler\OtpDevices\OtpDevicesManager;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\Templates\TemplatesManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Mirrorps\LaravelTaler\Wallet\WalletManager;
use Mirrorps\LaravelTaler\Tests\TestCase;
use RuntimeException;
use Taler\Taler as SdkTaler;

class TalerManagerTest extends TestCase
{
    public function test_it_builds_the_resource_managers_lazily(): void
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

        $bankAccounts = $manager->bankAccounts();
        $donauCharity = $manager->donauCharity();
        $config = $manager->config();
        $instance = $manager->instance();
        $inventory = $manager->inventory();
        $orders = $manager->orders();
        $otpDevices = $manager->otpDevices();
        $templates = $manager->templates();
        $twoFactorAuth = $manager->twoFactorAuth();
        $wallet = $manager->wallet();

        $this->assertInstanceOf(BankAccountsManager::class, $bankAccounts);
        $this->assertSame($bankAccounts, $manager->bankAccounts());
        $this->assertInstanceOf(DonauCharityManager::class, $donauCharity);
        $this->assertSame($donauCharity, $manager->donauCharity());
        $this->assertInstanceOf(ConfigManager::class, $config);
        $this->assertSame($config, $manager->config());
        $this->assertInstanceOf(InstanceManager::class, $instance);
        $this->assertSame($instance, $manager->instance());
        $this->assertInstanceOf(InventoryManager::class, $inventory);
        $this->assertSame($inventory, $manager->inventory());
        $this->assertInstanceOf(OrdersManager::class, $orders);
        $this->assertSame($orders, $manager->orders());
        $this->assertInstanceOf(OtpDevicesManager::class, $otpDevices);
        $this->assertSame($otpDevices, $manager->otpDevices());
        $this->assertInstanceOf(TemplatesManager::class, $templates);
        $this->assertSame($templates, $manager->templates());
        $this->assertInstanceOf(TwoFactorAuthManager::class, $twoFactorAuth);
        $this->assertSame($twoFactorAuth, $manager->twoFactorAuth());
        $this->assertInstanceOf(WalletManager::class, $wallet);
        $this->assertSame($wallet, $manager->wallet());
        $this->assertSame(['base_url' => 'https://merchant.example.test'], $manager->options());
    }
}
