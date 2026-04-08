<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Tests\Fakes\FakeOrdersClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Mirrorps\LaravelTaler\Wallet\WalletManager;
use stdClass;
use Taler\Api\Wallet\Dto\StatusPaidResponse;
use Taler\Api\Wallet\WalletClient;
use Taler\Taler as SdkTaler;

class WalletManagerTest extends TestCase
{
    public function test_it_proxies_get_order_calls_to_the_sdk_wallet_client(): void
    {
        $orderId = 'public-order-1';
        $params = ['session_id' => 'sess-1'];
        $headers = ['X-Test' => 'wallet'];
        $response = new StatusPaidResponse(
            refunded: false,
            refund_pending: false,
            refund_amount: 'EUR:0',
            refund_taken: 'EUR:0',
        );
        $asyncResponse = new stdClass();

        $walletClient = $this->createMock(WalletClient::class);
        $walletClient->expects($this->once())
            ->method('getOrder')
            ->with($orderId, $params, $headers)
            ->willReturn($response);
        $walletClient->expects($this->once())
            ->method('getOrderAsync')
            ->with($orderId, $params, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('wallet')
            ->willReturn($walletClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new WalletManager($factory);

        $this->assertSame($response, $manager->getOrder($orderId, $params, $headers));
        $this->assertSame($asyncResponse, $manager->getOrderAsync($orderId, $params, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
