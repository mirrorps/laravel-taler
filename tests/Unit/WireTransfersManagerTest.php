<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Mirrorps\LaravelTaler\WireTransfers\WireTransfersManager;
use stdClass;
use Taler\Api\WireTransfers\Dto\GetTransfersRequest;
use Taler\Api\WireTransfers\Dto\TransfersList;
use Taler\Api\WireTransfers\WireTransfersClient;
use Taler\Taler as SdkTaler;

class WireTransfersManagerTest extends TestCase
{
    public function test_it_proxies_get_transfers_calls_to_the_sdk_wire_transfers_client(): void
    {
        $request = new GetTransfersRequest(limit: 10, offset: 0);
        $headers = ['X-Test' => 'transfers'];
        $list = $this->createMock(TransfersList::class);
        $asyncResponse = new stdClass();

        $api = $this->createMock(WireTransfersClient::class);
        $api->expects($this->once())
            ->method('getTransfers')
            ->with($request, $headers)
            ->willReturn($list);
        $api->expects($this->once())
            ->method('getTransfersAsync')
            ->with(null, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('wireTransfers')
            ->willReturn($api);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WireTransfersManager($factory);

        $this->assertSame($list, $manager->getTransfers($request, $headers));
        $this->assertSame($asyncResponse, $manager->getTransfersAsync(null, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_transfer_calls_to_the_sdk_wire_transfers_client(): void
    {
        $tid = '42';
        $headers = ['X-Test' => 'delete-transfer'];
        $asyncResponse = new stdClass();

        $api = $this->createMock(WireTransfersClient::class);
        $api->expects($this->once())
            ->method('deleteTransfer')
            ->with($tid, $headers);
        $api->expects($this->once())
            ->method('deleteTransferAsync')
            ->with($tid, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('wireTransfers')
            ->willReturn($api);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WireTransfersManager($factory);

        $manager->deleteTransfer($tid, $headers);

        $this->assertSame($asyncResponse, $manager->deleteTransferAsync($tid, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
