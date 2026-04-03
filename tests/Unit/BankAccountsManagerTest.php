<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\BankAccounts\BankAccountsManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\BankAccounts\BankAccountClient;
use Taler\Api\BankAccounts\Dto\AccountAddDetails;
use Taler\Api\BankAccounts\Dto\AccountAddResponse;
use Taler\Api\BankAccounts\Dto\AccountPatchDetails;
use Taler\Api\BankAccounts\Dto\AccountsSummaryResponse;
use Taler\Api\BankAccounts\Dto\BankAccountDetail;
use Taler\Api\BankAccounts\Dto\BankAccountEntry;
use Taler\Taler as SdkTaler;

class BankAccountsManagerTest extends TestCase
{
    public function test_it_proxies_get_accounts_calls_to_the_sdk_bank_account_client(): void
    {
        $headers = ['X-Test' => 'accounts'];
        $response = new AccountsSummaryResponse([
            new BankAccountEntry(
                payto_uri: 'payto://iban/DE75512108001245126199?receiver-name=Sandbox',
                h_wire: 'wire-hash-1',
                active: true,
            ),
        ]);
        $asyncResponse = new stdClass();

        $bankAccountClient = $this->createMock(BankAccountClient::class);
        $bankAccountClient->expects($this->once())
            ->method('getAccounts')
            ->with($headers)
            ->willReturn($response);
        $bankAccountClient->expects($this->once())
            ->method('getAccountsAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('bankAccount')
            ->willReturn($bankAccountClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new BankAccountsManager($factory);

        $this->assertSame($response, $manager->getAccounts($headers));
        $this->assertSame($asyncResponse, $manager->getAccountsAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_get_account_calls_to_the_sdk_bank_account_client(): void
    {
        $hWire = 'wire-hash-1';
        $headers = ['X-Test' => 'account'];
        $response = new BankAccountDetail(
            payto_uri: 'payto://iban/DE75512108001245126199?receiver-name=Sandbox',
            h_wire: $hWire,
            salt: 'salt-value',
            active: true,
            credit_facade_url: 'https://facade.example.test',
        );
        $asyncResponse = new stdClass();

        $bankAccountClient = $this->createMock(BankAccountClient::class);
        $bankAccountClient->expects($this->once())
            ->method('getAccount')
            ->with($hWire, $headers)
            ->willReturn($response);
        $bankAccountClient->expects($this->once())
            ->method('getAccountAsync')
            ->with($hWire, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('bankAccount')
            ->willReturn($bankAccountClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new BankAccountsManager($factory);

        $this->assertSame($response, $manager->getAccount($hWire, $headers));
        $this->assertSame($asyncResponse, $manager->getAccountAsync($hWire, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_account_calls_to_the_sdk_bank_account_client(): void
    {
        $request = new AccountAddDetails(
            payto_uri: 'payto://iban/DE75512108001245126199?receiver-name=Sandbox',
            credit_facade_url: 'https://facade.example.test',
        );
        $headers = ['X-Test' => 'create-account'];
        $response = new AccountAddResponse(h_wire: 'wire-hash-1', salt: 'salt-value');
        $asyncResponse = new stdClass();

        $bankAccountClient = $this->createMock(BankAccountClient::class);
        $bankAccountClient->expects($this->once())
            ->method('createAccount')
            ->with($request, $headers)
            ->willReturn($response);
        $bankAccountClient->expects($this->once())
            ->method('createAccountAsync')
            ->with($request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('bankAccount')
            ->willReturn($bankAccountClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new BankAccountsManager($factory);

        $this->assertSame($response, $manager->createAccount($request, $headers));
        $this->assertSame($asyncResponse, $manager->createAccountAsync($request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_update_account_calls_to_the_sdk_bank_account_client(): void
    {
        $hWire = 'wire-hash-1';
        $request = new AccountPatchDetails(credit_facade_url: 'https://facade.example.test/updated');
        $headers = ['X-Test' => 'update-account'];
        $asyncResponse = new stdClass();

        $bankAccountClient = $this->createMock(BankAccountClient::class);
        $bankAccountClient->expects($this->once())
            ->method('updateAccount')
            ->with($hWire, $request, $headers);
        $bankAccountClient->expects($this->once())
            ->method('updateAccountAsync')
            ->with($hWire, $request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('bankAccount')
            ->willReturn($bankAccountClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new BankAccountsManager($factory);

        $manager->updateAccount($hWire, $request, $headers);

        $this->assertSame($asyncResponse, $manager->updateAccountAsync($hWire, $request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_account_calls_to_the_sdk_bank_account_client(): void
    {
        $hWire = 'wire-hash-1';
        $headers = ['X-Test' => 'delete-account'];
        $asyncResponse = new stdClass();

        $bankAccountClient = $this->createMock(BankAccountClient::class);
        $bankAccountClient->expects($this->once())
            ->method('deleteAccount')
            ->with($hWire, $headers);
        $bankAccountClient->expects($this->once())
            ->method('deleteAccountAsync')
            ->with($hWire, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('bankAccount')
            ->willReturn($bankAccountClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new BankAccountsManager($factory);

        $manager->deleteAccount($hWire, $headers);

        $this->assertSame($asyncResponse, $manager->deleteAccountAsync($hWire, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
