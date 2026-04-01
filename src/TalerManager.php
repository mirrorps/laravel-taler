<?php

namespace Mirrorps\LaravelTaler;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Taler\Taler as SdkTaler;

class TalerManager
{
    protected ?SdkTaler $client = null;

    protected ?OrdersManager $orders = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function orders(): OrdersManager
    {
        return $this->orders ??= new OrdersManager($this->factory);
    }

    /**
     * @return array<string, mixed>
     */
    public function options(): array
    {
        return $this->factory->options();
    }

    public function forgetClient(): self
    {
        $this->client = null;
        $this->orders = null;

        return $this;
    }
}
