<?php

namespace Mirrorps\LaravelTaler\Facades;

use Illuminate\Support\Facades\Facade;

class Taler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'taler';
    }
}
