<?php

namespace Mirrorps\LaravelTaler\OtpDevices;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\OtpDevices\Dto\GetOtpDeviceRequest;
use Taler\Api\OtpDevices\Dto\OtpDeviceAddDetails;
use Taler\Api\OtpDevices\Dto\OtpDeviceDetails;
use Taler\Api\OtpDevices\Dto\OtpDevicePatchDetails;
use Taler\Api\OtpDevices\Dto\OtpDevicesSummaryResponse;
use Taler\Api\OtpDevices\OtpDevicesClient;
use Taler\Taler as SdkTaler;

class OtpDevicesManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): OtpDevicesClient
    {
        return $this->client()->otpDevices();
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createOtpDevice(OtpDeviceAddDetails $details, array $headers = []): void
    {
        $this->api()->createOtpDevice($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function createOtpDeviceAsync(OtpDeviceAddDetails $details, array $headers = []): mixed
    {
        return $this->api()->createOtpDeviceAsync($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateOtpDevice(string $deviceId, OtpDevicePatchDetails $details, array $headers = []): void
    {
        $this->api()->updateOtpDevice($deviceId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function updateOtpDeviceAsync(string $deviceId, OtpDevicePatchDetails $details, array $headers = []): mixed
    {
        return $this->api()->updateOtpDeviceAsync($deviceId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @return OtpDevicesSummaryResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOtpDevices(array $headers = []): OtpDevicesSummaryResponse|array
    {
        return $this->api()->getOtpDevices($headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function getOtpDevicesAsync(array $headers = []): mixed
    {
        return $this->api()->getOtpDevicesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @return OtpDeviceDetails|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOtpDevice(string $deviceId, ?GetOtpDeviceRequest $request = null, array $headers = []): OtpDeviceDetails|array
    {
        return $this->api()->getOtpDevice($deviceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function getOtpDeviceAsync(string $deviceId, ?GetOtpDeviceRequest $request = null, array $headers = []): mixed
    {
        return $this->api()->getOtpDeviceAsync($deviceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteOtpDevice(string $deviceId, array $headers = []): void
    {
        $this->api()->deleteOtpDevice($deviceId, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function deleteOtpDeviceAsync(string $deviceId, array $headers = []): mixed
    {
        return $this->api()->deleteOtpDeviceAsync($deviceId, $headers);
    }
}
