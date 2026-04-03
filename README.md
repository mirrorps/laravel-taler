# Laravel Taler
> **Notice:** This package is under active development. File structure, APIs, and behavior may change at any time, and backward compatibility is not guaranteed until a stable release.


## Installation

This package is a Laravel wrapper around [`mirrorps/taler-php`](https://github.com/mirrorps/taler-php).
Laravel auto-discovers the service provider and facade, but you still need to:

1. Install the package
2. Install and bind a PSR-18 HTTP client
3. Configure the required Taler environment variables
4. Optionally publish the config file if you want app-level overrides
5. Clear cached config if your app uses it

Install the package:

```bash
composer require mirrorps/laravel-taler
```

## HTTP Client Setup

`laravel-taler` expects a PSR-18 HTTP client implementation.
If you want to use async package APIs the client must also support HTTPlug async requests.

A good default choice is the Guzzle 7 adapter:

```bash
composer require guzzlehttp/guzzle php-http/guzzle7-adapter
```

Then bind it in your Laravel app, for example in `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Http\Adapter\Guzzle7\Client as GuzzleAdapterClient;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GuzzleAdapterClient::class, function (): GuzzleAdapterClient {
            return new GuzzleAdapterClient();
        });

        $this->app->singleton(ClientInterface::class, function (): ClientInterface {
            return $this->app->make(GuzzleAdapterClient::class);
        });
    }
}
```

Notes:

- Sync-only package methods need a PSR-18 client.
- Async package methods need a client that also implements `Http\Client\HttpAsyncClient`.
- If you do not bind a compatible client, async APIs will fail at runtime.

## Package Configuration

You can configure the package entirely through `.env`.
Publishing `config/taler.php` is optional, because the package already merges its default config and reads the Taler settings from environment variables.

If you want to customize the config file inside your app, publish it with:

```bash
php artisan vendor:publish --tag=taler-config
```

This creates `config/taler.php` in your Laravel application.

Then configure your environment variables:

```dotenv
TALER_BASE_URL=https://backend.demo.taler.net/instances/sandbox
TALER_TOKEN="Bearer secret-token:sandbox"
TALER_USERNAME=merchant-user
TALER_PASSWORD=merchant-password
TALER_INSTANCE_ID=default
TALER_SCOPE=readonly
TALER_DURATION_US=3600000000
TALER_DESCRIPTION="Backoffice session"
TALER_WRAP_RESPONSE=true
TALER_DEBUG_LOGGING_ENABLED=false
```

Configuration notes:

- `TALER_BASE_URL` is required.
- Publishing `config/taler.php` is not required if the default package config and `.env` values are sufficient for your app.
- If `TALER_TOKEN` is set, it takes precedence over username/password login.
- If no token is provided, the package uses `TALER_USERNAME`, `TALER_PASSWORD`, and `TALER_INSTANCE_ID` to obtain a token.
- `TALER_SCOPE` defaults to `readonly`.
- `TALER_WRAP_RESPONSE` controls whether the underlying SDK wraps responses into DTOs when available.
- `TALER_DEBUG_LOGGING_ENABLED` enables SDK request/response logging through Laravel's logger.

If you cache config in your app, clear it after changing `.env`:

```bash
php artisan optimize:clear
```

## Installation Checklist

For a fresh Laravel app, the full setup looks like this:

```bash
composer require mirrorps/laravel-taler
composer require guzzlehttp/guzzle php-http/guzzle7-adapter
php artisan optimize:clear
```

After that:

1. Bind the HTTP client in `AppServiceProvider`
2. Add the Taler environment variables to `.env`
3. Optionally publish `config/taler.php` if you want to override package defaults in your app
4. Run a simple package call to verify connectivity

## Usage

Fetch order history:

```php
use Mirrorps\LaravelTaler\Facades\Taler;
use Taler\Api\Order\Dto\GetOrdersRequest;

$orders = Taler::orders()->getOrders(new GetOrdersRequest(
    paid: true,
    limit: 20,
));
```

Query a single order:

```php
use Mirrorps\LaravelTaler\Facades\Taler;
use Taler\Api\Order\Dto\GetOrderRequest;

$order = Taler::orders()->getOrder('order-123', new GetOrderRequest(
    token: 'claim-token',
));
```

Async calls are available too:

```php
$promise = Taler::orders()->getOrdersAsync(['limit' => 20]);
$orderPromise = Taler::orders()->getOrderAsync('order-123');
```

If you want the resolved result immediately:

```php
$orders = Taler::orders()->getOrdersAsync(['limit' => 20])->wait();
$order = Taler::orders()->getOrderAsync('order-123')->wait();
```

## Testing

```bash
composer test
```
