<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\DonauCharity\DonauCharityManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\DonauCharity\DonauCharityClient;
use Taler\Api\DonauCharity\Dto\DonauInstancesResponse;
use Taler\Api\DonauCharity\Dto\PostDonauRequest;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class DonauCharityManagerTest extends TestCase
{
    public function test_it_proxies_get_instances_calls_to_the_sdk_donau_charity_client(): void
    {
        $headers = ['X-Test' => 'donau'];
        $response = new DonauInstancesResponse(donau_instances: []);
        $asyncResponse = new stdClass();

        $donauClient = $this->createMock(DonauCharityClient::class);
        $donauClient->expects($this->once())
            ->method('getInstances')
            ->with($headers)
            ->willReturn($response);
        $donauClient->expects($this->once())
            ->method('getInstancesAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('donauCharity')
            ->willReturn($donauClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new DonauCharityManager($factory);

        $this->assertSame($response, $manager->getInstances($headers));
        $this->assertSame($asyncResponse, $manager->getInstancesAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_donau_charity_calls_to_the_sdk_client(): void
    {
        $request = new PostDonauRequest('https://donau.example', 7);
        $headers = ['X-Test' => 'create'];
        $challenge = new ChallengeResponse(challenges: [], combi_and: false);
        $asyncResponse = new stdClass();

        $donauClient = $this->createMock(DonauCharityClient::class);
        $donauClient->expects($this->once())
            ->method('createDonauCharity')
            ->with($request, $headers)
            ->willReturn($challenge);
        $donauClient->expects($this->once())
            ->method('createDonauCharityAsync')
            ->with($request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('donauCharity')
            ->willReturn($donauClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new DonauCharityManager($factory);

        $this->assertSame($challenge, $manager->createDonauCharity($request, $headers));
        $this->assertSame($asyncResponse, $manager->createDonauCharityAsync($request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_donau_charity_by_serial_calls_to_the_sdk_client(): void
    {
        $serial = 321;
        $headers = ['X-Test' => 'delete'];
        $asyncResponse = new stdClass();

        $donauClient = $this->createMock(DonauCharityClient::class);
        $donauClient->expects($this->once())
            ->method('deleteDonauCharityBySerial')
            ->with($serial, $headers);
        $donauClient->expects($this->once())
            ->method('deleteDonauCharityBySerialAsync')
            ->with($serial, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('donauCharity')
            ->willReturn($donauClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new DonauCharityManager($factory);

        $manager->deleteDonauCharityBySerial($serial, $headers);

        $this->assertSame($asyncResponse, $manager->deleteDonauCharityBySerialAsync($serial, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
