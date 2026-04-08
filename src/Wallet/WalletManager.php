<?php

namespace Mirrorps\LaravelTaler\Wallet;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Wallet\Dto\StatusGotoResponse;
use Taler\Api\Wallet\Dto\StatusPaidResponse;
use Taler\Api\Wallet\Dto\StatusUnpaidResponse;
use Taler\Api\Wallet\WalletClient;
use Taler\Taler as SdkTaler;

class WalletManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): WalletClient
    {
        return $this->client()->wallet();
    }

    /**
     * @param array<string, string> $params HTTP query parameters
     * @param array<string, string> $headers Optional request headers
     *
     * @return StatusPaidResponse|StatusGotoResponse|StatusUnpaidResponse
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrder(string $orderId, array $params = [], array $headers = []): StatusPaidResponse|StatusGotoResponse|StatusUnpaidResponse
    {
        return $this->api()->getOrder($orderId, $params, $headers);
    }

    /**
     * @param array<string, string> $params HTTP query parameters
     * @param array<string, string> $headers Optional request headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrderAsync(string $orderId, array $params = [], array $headers = []): mixed
    {
        return $this->api()->getOrderAsync($orderId, $params, $headers);
    }
}
