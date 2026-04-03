<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeOrdersClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Order\Dto\Amount;
use Taler\Api\Order\Dto\ForgetRequest;
use Taler\Api\Order\Dto\GetOrderRequest;
use Taler\Api\Order\Dto\GetOrdersRequest;
use Taler\Api\Order\Dto\MerchantRefundResponse;
use Taler\Api\Order\Dto\OrderV0;
use Taler\Api\Order\Dto\PostOrderRequest;
use Taler\Api\Order\Dto\PostOrderResponse;
use Taler\Api\Order\Dto\RefundRequest;
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

    public function test_it_proxies_create_order_calls_to_the_sdk_order_client(): void
    {
        $request = $this->makePostOrderRequest();
        $headers = ['X-Test' => 'create-order'];
        $response = new PostOrderResponse(order_id: 'order-123', token: 'claim-token');
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('createOrder')
            ->with($request, $headers)
            ->willReturn($response);
        $orderClient->expects($this->once())
            ->method('createOrderAsync')
            ->with($request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $this->assertSame($response, $manager->createOrder($request, $headers));
        $this->assertSame($asyncResponse, $manager->createOrderAsync($request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_refund_order_calls_to_the_sdk_order_client(): void
    {
        $orderId = 'order-123';
        $request = new RefundRequest(refund: 'EUR:5.00', reason: 'Customer requested a refund');
        $headers = ['X-Test' => 'refund-order'];
        $response = new MerchantRefundResponse(
            taler_refund_uri: 'taler://refund/example',
            h_contract: 'contract-hash',
        );
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('refundOrder')
            ->with($orderId, $request, $headers)
            ->willReturn($response);
        $orderClient->expects($this->once())
            ->method('refundOrderAsync')
            ->with($orderId, $request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $this->assertSame($response, $manager->refundOrder($orderId, $request, $headers));
        $this->assertSame($asyncResponse, $manager->refundOrderAsync($orderId, $request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_order_calls_to_the_sdk_order_client(): void
    {
        $orderId = 'order-123';
        $headers = ['X-Test' => 'delete-order'];
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('deleteOrder')
            ->with($orderId, $headers);
        $orderClient->expects($this->once())
            ->method('deleteOrderAsync')
            ->with($orderId, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $manager->deleteOrder($orderId, $headers);

        $this->assertSame($asyncResponse, $manager->deleteOrderAsync($orderId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_forget_order_calls_to_the_sdk_order_client(): void
    {
        $orderId = 'order-123';
        $request = new ForgetRequest(fields: ['$.delivery_location']);
        $headers = ['X-Test' => 'forget-order'];
        $asyncResponse = new stdClass();

        $orderClient = $this->createMock(OrderClient::class);
        $orderClient->expects($this->once())
            ->method('forgetOrder')
            ->with($orderId, $request, $headers);
        $orderClient->expects($this->once())
            ->method('forgetOrderAsync')
            ->with($orderId, $request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('order')
            ->willReturn($orderClient);

        $factory = new FakeOrdersClientFactory($sdk);
        $manager = new OrdersManager($factory);

        $manager->forgetOrder($orderId, $request, $headers);

        $this->assertSame($asyncResponse, $manager->forgetOrderAsync($orderId, $request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    private function makePostOrderRequest(): PostOrderRequest
    {
        return new PostOrderRequest(
            order: new OrderV0(
                summary: 'Test order',
                amount: new Amount('EUR:10.00'),
                fulfillment_url: 'https://merchant.example.test/orders/order-123',
            ),
        );
    }
}
