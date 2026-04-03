<?php

namespace Mirrorps\LaravelTaler\BankAccounts;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\BankAccounts\BankAccountClient;
use Taler\Api\BankAccounts\Dto\AccountAddDetails;
use Taler\Api\BankAccounts\Dto\AccountAddResponse;
use Taler\Api\BankAccounts\Dto\AccountsSummaryResponse;
use Taler\Api\BankAccounts\Dto\AccountPatchDetails;
use Taler\Api\BankAccounts\Dto\BankAccountDetail;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class BankAccountsManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): BankAccountClient
    {
        return $this->client()->bankAccount();
    }

    /**
     * @param array<string, string> $headers
     * @return AccountsSummaryResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccounts(array $headers = []): AccountsSummaryResponse|array
    {
        return $this->api()->getAccounts($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccountsAsync(array $headers = []): mixed
    {
        return $this->api()->getAccountsAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return BankAccountDetail|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccount(string $hWire, array $headers = []): BankAccountDetail|array
    {
        return $this->api()->getAccount($hWire, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccountAsync(string $hWire, array $headers = []): mixed
    {
        return $this->api()->getAccountAsync($hWire, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return AccountAddResponse|ChallengeResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createAccount(AccountAddDetails $details, array $headers = []): AccountAddResponse|ChallengeResponse|array
    {
        return $this->api()->createAccount($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createAccountAsync(AccountAddDetails $details, array $headers = []): mixed
    {
        return $this->api()->createAccountAsync($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateAccount(string $hWire, AccountPatchDetails $details, array $headers = []): void
    {
        $this->api()->updateAccount($hWire, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateAccountAsync(string $hWire, AccountPatchDetails $details, array $headers = []): mixed
    {
        return $this->api()->updateAccountAsync($hWire, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccount(string $hWire, array $headers = []): void
    {
        $this->api()->deleteAccount($hWire, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccountAsync(string $hWire, array $headers = []): mixed
    {
        return $this->api()->deleteAccountAsync($hWire, $headers);
    }
}
