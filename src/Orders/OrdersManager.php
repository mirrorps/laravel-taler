<?php

namespace Mirrorps\LaravelTaler\Orders;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
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

    public function api(): mixed
    {
        return $this->client()->order();
    }
}
