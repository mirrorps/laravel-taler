<?php

namespace Mirrorps\LaravelTaler\Orders;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Order\Dto\CheckPaymentClaimedResponse;
use Taler\Api\Order\Dto\CheckPaymentPaidResponse;
use Taler\Api\Order\Dto\CheckPaymentUnpaidResponse;
use Taler\Api\Order\Dto\GetOrderRequest;
use Taler\Api\Order\Dto\GetOrdersRequest;
use Taler\Api\Order\Dto\OrderHistory;
use Taler\Api\Order\OrderClient;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class OrdersManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): OrderClient
    {
        return $this->client()->order();
    }

    /**
     * @param GetOrdersRequest|array<string, scalar>|null $request
     * @param array<string, string> $headers
     * @return OrderHistory|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrders(GetOrdersRequest|array|null $request = null, array $headers = []): OrderHistory|array
    {
        return $this->api()->getOrders($request, $headers);
    }

    /**
     * @param GetOrdersRequest|array<string, scalar>|null $request
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrdersAsync(GetOrdersRequest|array|null $request = null, array $headers = []): mixed
    {
        return $this->api()->getOrdersAsync($request, $headers);
    }

    /**
     * @param GetOrderRequest|array<string, scalar>|null $request
     * @param array<string, string> $headers
     * @return CheckPaymentPaidResponse|CheckPaymentClaimedResponse|CheckPaymentUnpaidResponse|ChallengeResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrder(string $orderId, GetOrderRequest|array|null $request = null, array $headers = []): CheckPaymentPaidResponse|CheckPaymentClaimedResponse|CheckPaymentUnpaidResponse|ChallengeResponse|array
    {
        return $this->api()->getOrder($orderId, $request, $headers);
    }

    /**
     * @param GetOrderRequest|array<string, scalar>|null $request
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getOrderAsync(string $orderId, GetOrderRequest|array|null $request = null, array $headers = []): mixed
    {
        return $this->api()->getOrderAsync($orderId, $request, $headers);
    }
}
