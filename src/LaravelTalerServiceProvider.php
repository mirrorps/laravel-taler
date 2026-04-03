<?php

namespace Mirrorps\LaravelTaler;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
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
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/taler.php' => config_path('taler.php'),
        ], 'taler-config');
    }
}
