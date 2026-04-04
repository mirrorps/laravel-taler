<?php

namespace Mirrorps\LaravelTaler;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Taler\Taler as SdkTaler;

class TalerManager
{
    protected ?SdkTaler $client = null;

    protected ?BankAccountsManager $bankAccounts = null;

    protected ?ConfigManager $config = null;

    protected ?OrdersManager $orders = null;

    protected ?TwoFactorAuthManager $twoFactorAuth = null;

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

    public function bankAccounts(): BankAccountsManager
    {
        return $this->bankAccounts ??= new BankAccountsManager($this->factory);
    }

    public function config(): ConfigManager
    {
        return $this->config ??= new ConfigManager($this->factory);
    }

    public function twoFactorAuth(): TwoFactorAuthManager
    {
        return $this->twoFactorAuth ??= new TwoFactorAuthManager($this->factory);
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
        $this->bankAccounts = null;
        $this->config = null;
        $this->orders = null;
        $this->twoFactorAuth = null;

        return $this;
    }
}
