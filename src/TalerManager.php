<?php

namespace Mirrorps\LaravelTaler;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Mirrorps\LaravelTaler\DonauCharity\DonauCharityManager;
use Mirrorps\LaravelTaler\Inventory\InventoryManager;
use Mirrorps\LaravelTaler\OtpDevices\OtpDevicesManager;
use Mirrorps\LaravelTaler\Orders\OrdersManager;
use Mirrorps\LaravelTaler\Templates\TemplatesManager;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use Mirrorps\LaravelTaler\Wallet\WalletManager;
use Taler\Taler as SdkTaler;

class TalerManager
{
    protected ?SdkTaler $client = null;

    protected ?BankAccountsManager $bankAccounts = null;

    protected ?DonauCharityManager $donauCharity = null;

    protected ?ConfigManager $config = null;

    protected ?InventoryManager $inventory = null;

    protected ?OrdersManager $orders = null;

    protected ?OtpDevicesManager $otpDevices = null;

    protected ?TemplatesManager $templates = null;

    protected ?TwoFactorAuthManager $twoFactorAuth = null;

    protected ?WalletManager $wallet = null;

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

    public function donauCharity(): DonauCharityManager
    {
        return $this->donauCharity ??= new DonauCharityManager($this->factory);
    }

    public function config(): ConfigManager
    {
        return $this->config ??= new ConfigManager($this->factory);
    }

    public function inventory(): InventoryManager
    {
        return $this->inventory ??= new InventoryManager($this->factory);
    }

    public function twoFactorAuth(): TwoFactorAuthManager
    {
        return $this->twoFactorAuth ??= new TwoFactorAuthManager($this->factory);
    }

    public function otpDevices(): OtpDevicesManager
    {
        return $this->otpDevices ??= new OtpDevicesManager($this->factory);
    }

    public function templates(): TemplatesManager
    {
        return $this->templates ??= new TemplatesManager($this->factory);
    }

    public function wallet(): WalletManager
    {
        return $this->wallet ??= new WalletManager($this->factory);
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
        $this->donauCharity = null;
        $this->config = null;
        $this->inventory = null;
        $this->orders = null;
        $this->otpDevices = null;
        $this->templates = null;
        $this->twoFactorAuth = null;
        $this->wallet = null;

        return $this;
    }
}
