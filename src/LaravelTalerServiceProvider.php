<?php

namespace Mirrorps\LaravelTaler;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\DonauCharity\DonauCharityManager;
use Mirrorps\LaravelTaler\Instance\InstanceManager;
use Mirrorps\LaravelTaler\Inventory\InventoryManager;
use Mirrorps\LaravelTaler\OtpDevices\OtpDevicesManager;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\Templates\TemplatesManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Mirrorps\LaravelTaler\Wallet\WalletManager;
use Psr\Http\Client\ClientInterface;

class LaravelTalerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/taler.php', 'taler');

        $this->app->singleton(CreatesTalerClients::class, function (Application $app): TalerClientFactory {
            return new TalerClientFactory(
                $app['config'],
                $app['log'],
                $app->bound(ClientInterface::class) ? $app->make(ClientInterface::class) : null,
            );
        });

        $this->app->singleton(TalerManager::class, function (Application $app): TalerManager {
            return new TalerManager($app->make(CreatesTalerClients::class));
        });

        $this->app->alias(TalerManager::class, 'taler');

        $this->app->bind(OrdersManager::class, function (Application $app): OrdersManager {
            return $app->make(TalerManager::class)->orders();
        });

        $this->app->bind(BankAccountsManager::class, function (Application $app): BankAccountsManager {
            return $app->make(TalerManager::class)->bankAccounts();
        });

        $this->app->bind(ConfigManager::class, function (Application $app): ConfigManager {
            return $app->make(TalerManager::class)->config();
        });

        $this->app->bind(InstanceManager::class, function (Application $app): InstanceManager {
            return $app->make(TalerManager::class)->instance();
        });

        $this->app->bind(InventoryManager::class, function (Application $app): InventoryManager {
            return $app->make(TalerManager::class)->inventory();
        });

        $this->app->bind(TwoFactorAuthManager::class, function (Application $app): TwoFactorAuthManager {
            return $app->make(TalerManager::class)->twoFactorAuth();
        });

        $this->app->bind(OtpDevicesManager::class, function (Application $app): OtpDevicesManager {
            return $app->make(TalerManager::class)->otpDevices();
        });

        $this->app->bind(TemplatesManager::class, function (Application $app): TemplatesManager {
            return $app->make(TalerManager::class)->templates();
        });

        $this->app->bind(WalletManager::class, function (Application $app): WalletManager {
            return $app->make(TalerManager::class)->wallet();
        });

        $this->app->bind(DonauCharityManager::class, function (Application $app): DonauCharityManager {
            return $app->make(TalerManager::class)->donauCharity();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/taler.php' => config_path('taler.php'),
        ], 'taler-config');
    }
}
