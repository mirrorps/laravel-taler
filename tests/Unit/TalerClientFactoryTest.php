<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use InvalidArgumentException;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TalerClientFactoryTest extends TestCase
{
    public function test_it_transforms_laravel_config_into_sdk_options(): void
    {
        config()->set('taler.base_url', 'https://merchant.example.test/instances/demo');
        config()->set('taler.username', 'merchant-user');
        config()->set('taler.password', 'merchant-password');
        config()->set('taler.instance_id', 'demo');
        config()->set('taler.scope', 'order-full');
        config()->set('taler.duration_us', 3600000000);
        config()->set('taler.description', 'Backoffice session');
        config()->set('taler.wrap_response', false);
        config()->set('taler.debug_logging_enabled', true);

        $options = $this->app->make(CreatesTalerClients::class)->options();

        $this->assertSame('https://merchant.example.test/instances/demo', $options['base_url']);
        $this->assertSame('merchant-user', $options['username']);
        $this->assertSame('merchant-password', $options['password']);
        $this->assertSame('demo', $options['instance']);
        $this->assertSame('order-full', $options['scope']);
        $this->assertSame(3600000000, $options['duration_us']);
        $this->assertSame('Backoffice session', $options['description']);
        $this->assertFalse($options['wrapResponse']);
        $this->assertTrue($options['debugLoggingEnabled']);
        $this->assertArrayHasKey('logger', $options);
        $this->assertArrayNotHasKey('client', $options);
    }

    public function test_it_passes_a_bound_psr18_http_client_to_the_sdk_options(): void
    {
        $client = new class implements ClientInterface {
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                throw new \RuntimeException('Not implemented for this test.');
            }
        };

        $this->app->instance(ClientInterface::class, $client);

        $options = $this->app->make(CreatesTalerClients::class)->options();

        $this->assertArrayHasKey('client', $options);
        $this->assertSame($client, $options['client']);
    }

    public function test_it_throws_a_clear_exception_when_base_url_is_missing(): void
    {
        config()->set('taler.base_url', null);

        $factory = $this->app->make(CreatesTalerClients::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Taler is not configured. Set `taler.base_url` or define the `TALER_BASE_URL` environment variable.'
        );

        $factory->make();
    }
}
