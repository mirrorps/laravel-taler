<?php

namespace Mirrorps\LaravelTaler\DonauCharity;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\DonauCharity\DonauCharityClient;
use Taler\Api\DonauCharity\Dto\DonauInstancesResponse;
use Taler\Api\DonauCharity\Dto\PostDonauRequest;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class DonauCharityManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): DonauCharityClient
    {
        return $this->client()->donauCharity();
    }

    /**
     * @param array<string, string> $headers
     *
     * @return DonauInstancesResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstances(array $headers = []): DonauInstancesResponse|array
    {
        return $this->api()->getInstances($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstancesAsync(array $headers = []): mixed
    {
        return $this->api()->getInstancesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createDonauCharity(PostDonauRequest $request, array $headers = []): ?ChallengeResponse
    {
        return $this->api()->createDonauCharity($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createDonauCharityAsync(PostDonauRequest $request, array $headers = []): mixed
    {
        return $this->api()->createDonauCharityAsync($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteDonauCharityBySerial(int $donauSerial, array $headers = []): void
    {
        $this->api()->deleteDonauCharityBySerial($donauSerial, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteDonauCharityBySerialAsync(int $donauSerial, array $headers = []): mixed
    {
        return $this->api()->deleteDonauCharityBySerialAsync($donauSerial, $headers);
    }
}
