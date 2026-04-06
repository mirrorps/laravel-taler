<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\OtpDevices\OtpDevicesManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\OtpDevices\Dto\GetOtpDeviceRequest;
use Taler\Api\OtpDevices\Dto\OtpDeviceAddDetails;
use Taler\Api\OtpDevices\Dto\OtpDeviceDetails;
use Taler\Api\OtpDevices\Dto\OtpDevicePatchDetails;
use Taler\Api\OtpDevices\Dto\OtpDevicesSummaryResponse;
use Taler\Api\OtpDevices\OtpDevicesClient;
use Taler\Taler as SdkTaler;

class OtpDevicesManagerTest extends TestCase
{
    public function test_it_proxies_create_and_update_calls_to_the_sdk_otp_devices_client(): void
    {
        $add = new OtpDeviceAddDetails(
            otp_device_id: 'dev-1',
            otp_device_description: 'POS',
            otp_key: 'SECRET',
            otp_algorithm: 1,
        );
        $patch = new OtpDevicePatchDetails(otp_device_description: 'Updated');
        $headers = ['X-Test' => 'otp'];
        $asyncCreate = new stdClass();
        $asyncUpdate = new stdClass();

        $otpClient = $this->createMock(OtpDevicesClient::class);
        $otpClient->expects($this->once())
            ->method('createOtpDevice')
            ->with($add, $headers);
        $otpClient->expects($this->once())
            ->method('createOtpDeviceAsync')
            ->with($add, $headers)
            ->willReturn($asyncCreate);
        $otpClient->expects($this->once())
            ->method('updateOtpDevice')
            ->with('dev-1', $patch, $headers);
        $otpClient->expects($this->once())
            ->method('updateOtpDeviceAsync')
            ->with('dev-1', $patch, $headers)
            ->willReturn($asyncUpdate);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(4))
            ->method('otpDevices')
            ->willReturn($otpClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new OtpDevicesManager($factory);

        $manager->createOtpDevice($add, $headers);
        $this->assertSame($asyncCreate, $manager->createOtpDeviceAsync($add, $headers));
        $manager->updateOtpDevice('dev-1', $patch, $headers);
        $this->assertSame($asyncUpdate, $manager->updateOtpDeviceAsync('dev-1', $patch, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_list_and_get_and_delete_calls_to_the_sdk_otp_devices_client(): void
    {
        $headers = ['X-Otp' => '1'];
        $request = new GetOtpDeviceRequest(faketime: 123, price: 'EUR:1');
        $summary = $this->createMock(OtpDevicesSummaryResponse::class);
        $details = $this->createMock(OtpDeviceDetails::class);
        $asyncList = new stdClass();
        $asyncGet = new stdClass();
        $asyncDelete = new stdClass();

        $otpClient = $this->createMock(OtpDevicesClient::class);
        $otpClient->expects($this->once())
            ->method('getOtpDevices')
            ->with($headers)
            ->willReturn($summary);
        $otpClient->expects($this->once())
            ->method('getOtpDevicesAsync')
            ->with($headers)
            ->willReturn($asyncList);
        $otpClient->expects($this->once())
            ->method('getOtpDevice')
            ->with('dev-1', $request, $headers)
            ->willReturn($details);
        $otpClient->expects($this->once())
            ->method('getOtpDeviceAsync')
            ->with('dev-1', $request, $headers)
            ->willReturn($asyncGet);
        $otpClient->expects($this->once())
            ->method('deleteOtpDevice')
            ->with('dev-1', $headers);
        $otpClient->expects($this->once())
            ->method('deleteOtpDeviceAsync')
            ->with('dev-1', $headers)
            ->willReturn($asyncDelete);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(6))
            ->method('otpDevices')
            ->willReturn($otpClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new OtpDevicesManager($factory);

        $this->assertSame($summary, $manager->getOtpDevices($headers));
        $this->assertSame($asyncList, $manager->getOtpDevicesAsync($headers));
        $this->assertSame($details, $manager->getOtpDevice('dev-1', $request, $headers));
        $this->assertSame($asyncGet, $manager->getOtpDeviceAsync('dev-1', $request, $headers));
        $manager->deleteOtpDevice('dev-1', $headers);
        $this->assertSame($asyncDelete, $manager->deleteOtpDeviceAsync('dev-1', $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
