<?php

namespace Mirrorps\LaravelTaler\Config;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Config\ConfigClient;
use Taler\Api\Config\Dto\MerchantVersionResponse;
use Taler\Taler as SdkTaler;

class ConfigManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): ConfigClient
    {
        return $this->client()->configApi();
    }

    /**
     * @param array<string, string> $headers
     * @return MerchantVersionResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getConfig(array $headers = []): MerchantVersionResponse|array
    {
        return $this->api()->getConfig($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getConfigAsync(array $headers = []): mixed
    {
        return $this->api()->getConfigAsync($headers);
    }
}
