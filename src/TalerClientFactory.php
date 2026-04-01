<?php

namespace Mirrorps\LaravelTaler;

use Illuminate\Contracts\Config\Repository;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Taler\Factory\Factory;
use Taler\Taler as SdkTaler;

class TalerClientFactory implements CreatesTalerClients
{
    public function __construct(
        protected Repository $config,
        protected LoggerInterface $logger,
        protected ?ClientInterface $httpClient = null,
    ) {
    }

    public function make(): SdkTaler
    {
        return Factory::create($this->options());
    }

    public function options(): array
    {
        return array_filter([
            'base_url' => $this->config->get('taler.base_url'),
            'token' => $this->config->get('taler.token'),
            'username' => $this->config->get('taler.username'),
            'password' => $this->config->get('taler.password'),
            'instance' => $this->config->get('taler.instance_id'),
            'scope' => $this->config->get('taler.scope'),
            'duration_us' => $this->config->get('taler.duration_us'),
            'description' => $this->config->get('taler.description'),
            'wrapResponse' => $this->config->get('taler.wrap_response', true),
            'logger' => $this->logger,
            'debugLoggingEnabled' => $this->config->get('taler.debug_logging_enabled', false),
            'httpClient' => $this->httpClient,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
