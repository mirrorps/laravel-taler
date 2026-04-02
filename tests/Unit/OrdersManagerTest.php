<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeOrdersClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Order\Dto\GetOrderRequest;
use Taler\Api\Order\Dto\GetOrdersRequest;
use Taler\Api\Order\OrderClient;
use Taler\Taler as SdkTaler;

class OrdersManagerTest extends TestCase
{
    public function test_it_proxies_get_orders_calls_to_the_sdk_order_client(): void
    {
        $request = new GetOrdersRequest(limit: 10, paid: true);
        $headers = ['X-Test' => 'orders'];
        $history = ['orders' => []];
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('getOrders')
            ->with($request, $headers)
            ->willReturn($history);
        $orderClient->expects($this->once())
            ->method('getOrdersAsync')
            ->with(['limit' => 5], $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $this->assertSame($history, $manager->getOrders($request, $headers));
        $this->assertSame($asyncResponse, $manager->getOrdersAsync(['limit' => 5], $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_get_order_calls_to_the_sdk_order_client(): void
    {
        $orderId = 'order-123';
        $request = new GetOrderRequest(token: 'claim-token');
        $headers = ['X-Test' => 'order'];
        $response = ['order_status' => 'unpaid'];
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('getOrder')
            ->with($orderId, $request, $headers)
            ->willReturn($response);
        $orderClient->expects($this->once())
            ->method('getOrderAsync')
            ->with($orderId, ['token' => 'claim-token'], $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $this->assertSame($response, $manager->getOrder($orderId, $request, $headers));
        $this->assertSame($asyncResponse, $manager->getOrderAsync($orderId, ['token' => 'claim-token'], $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
