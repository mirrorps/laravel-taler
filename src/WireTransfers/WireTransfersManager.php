<?php

namespace Mirrorps\LaravelTaler\WireTransfers;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\WireTransfers\Dto\GetTransfersRequest;
use Taler\Api\WireTransfers\Dto\TransfersList;
use Taler\Api\WireTransfers\WireTransfersClient;
use Taler\Taler as SdkTaler;

class WireTransfersManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): WireTransfersClient
    {
        return $this->client()->wireTransfers();
    }

    /**
     * @param array<string, string> $headers
     * @return TransfersList|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTransfers(?GetTransfersRequest $request = null, array $headers = []): TransfersList|array
    {
        return $this->api()->getTransfers($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTransfersAsync(?GetTransfersRequest $request = null, array $headers = []): mixed
    {
        return $this->api()->getTransfersAsync($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTransfer(string $tid, array $headers = []): void
    {
        $this->api()->deleteTransfer($tid, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTransferAsync(string $tid, array $headers = []): mixed
    {
        return $this->api()->deleteTransferAsync($tid, $headers);
    }
}
