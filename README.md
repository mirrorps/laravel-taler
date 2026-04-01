# Laravel Taler
> **Notice:** This package is under active development. File structure, APIs, and behavior may change at any time, and backward compatibility is not guaranteed until a stable release.


## Installation

```bash
composer require mirrorps/laravel-taler
```

## Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=taler-config
```

Then configure your environment:

```dotenv
TALER_BASE_URL=https://backend.demo.taler.net/instances/sandbox
TALER_TOKEN="Bearer secret-token:sandbox"
TALER_USERNAME=merchant-user
TALER_PASSWORD=merchant-password
TALER_INSTANCE_ID=default
TALER_SCOPE=readonly
```

If `TALER_TOKEN` is set, it takes precedence. Otherwise the package is prepared to use username/password authentication with the merchant backend instance id.

## HTTP Client Integration

This package expects a PSR-18 compatible HTTP client implementation.


## Testing

```bash
composer test
```
