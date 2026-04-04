<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TalerManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
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
        $config = $manager->config();
        $orders = $manager->orders();
        $twoFactorAuth = $manager->twoFactorAuth();

        $this->assertInstanceOf(BankAccountsManager::class, $bankAccounts);
        $this->assertSame($bankAccounts, $manager->bankAccounts());
        $this->assertInstanceOf(ConfigManager::class, $config);
        $this->assertSame($config, $manager->config());
        $this->assertInstanceOf(OrdersManager::class, $orders);
        $this->assertSame($orders, $manager->orders());
        $this->assertInstanceOf(TwoFactorAuthManager::class, $twoFactorAuth);
        $this->assertSame($twoFactorAuth, $manager->twoFactorAuth());
        $this->assertSame(['base_url' => 'https://merchant.example.test'], $manager->options());
    }
}
