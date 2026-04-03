<?php

namespace Mirrorps\LaravelTaler\Tests\Fakes;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Taler as SdkTaler;

final class FakeTalerClientFactory implements CreatesTalerClients
{
    public int $makeCalls = 0;

    public function __construct(private SdkTaler $client)
    {
    }

    public function make(): SdkTaler
    {
        $this->makeCalls++;

        return $this->client;
    }

    public function options(): array
    {
        return [];
    }
}
