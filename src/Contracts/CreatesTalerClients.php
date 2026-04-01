<?php

namespace Mirrorps\LaravelTaler\Contracts;

use Taler\Taler as SdkTaler;

interface CreatesTalerClients
{
    public function make(): SdkTaler;

    /**
     * @return array<string, mixed>
     */
    public function options(): array;
}
