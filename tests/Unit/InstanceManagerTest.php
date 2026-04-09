<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Instance\InstanceManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Instance\Dto\GetAccessTokensRequest;
use Taler\Api\Instance\Dto\GetKycStatusRequest;
use Taler\Api\Instance\Dto\GetMerchantStatisticsAmountRequest;
use Taler\Api\Instance\Dto\GetMerchantStatisticsCounterRequest;
use Taler\Api\Instance\Dto\InstanceAuthConfigToken;
use Taler\Api\Instance\Dto\InstanceConfigurationMessage;
use Taler\Api\Instance\Dto\InstanceReconfigurationMessage;
use Taler\Api\Instance\Dto\LoginTokenRequest;
use Taler\Api\Instance\InstanceClient;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class InstanceManagerTest extends TestCase
{
    public function test_it_proxies_get_instances_and_get_instance_to_the_sdk_instance_client(): void
    {
        $headers = ['X-Test' => 'instances'];
        $list = ['instances' => []];
        $query = ['instance' => 'default'];
        $asyncList = new stdClass();
        $asyncQuery = new stdClass();

        $instanceClient = $this->createMock(InstanceClient::class);
        $instanceClient->expects($this->once())
            ->method('getInstances')
            ->with($headers)
            ->willReturn($list);
        $instanceClient->expects($this->once())
            ->method('getInstancesAsync')
            ->with($headers)
            ->willReturn($asyncList);
        $instanceClient->expects($this->once())
            ->method('getInstance')
            ->with('default', $headers)
            ->willReturn($query);
        $instanceClient->expects($this->once())
            ->method('getInstanceAsync')
            ->with('default', $headers)
            ->willReturn($asyncQuery);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(4))
            ->method('instance')
            ->willReturn($instanceClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new InstanceManager($factory);

        $this->assertSame($list, $manager->getInstances($headers));
        $this->assertSame($asyncList, $manager->getInstancesAsync($headers));
        $this->assertSame($query, $manager->getInstance('default', $headers));
        $this->assertSame($asyncQuery, $manager->getInstanceAsync('default', $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_update_and_delete_instance_to_the_sdk_instance_client(): void
    {
        $headers = ['X-Test' => 'cud'];
        $config = $this->minimalInstanceConfigurationMessage();
        $reconfig = $this->minimalInstanceReconfigurationMessage();
        $challenge = $this->createMock(ChallengeResponse::class);
        $asyncCreate = new stdClass();
        $asyncUpdate = new stdClass();
        $asyncDelete = new stdClass();

        $instanceClient = $this->createMock(InstanceClient::class);
        $instanceClient->expects($this->once())
            ->method('createInstance')
            ->with($config, $headers);
        $instanceClient->expects($this->once())
            ->method('createInstanceAsync')
            ->with($config, $headers)
            ->willReturn($asyncCreate);
        $instanceClient->expects($this->once())
            ->method('updateInstance')
            ->with('inst1', $reconfig, $headers);
        $instanceClient->expects($this->once())
            ->method('updateInstanceAsync')
            ->with('inst1', $reconfig, $headers)
            ->willReturn($asyncUpdate);
        $instanceClient->expects($this->once())
            ->method('deleteInstance')
            ->with('inst1', true, $headers)
            ->willReturn($challenge);
        $instanceClient->expects($this->once())
            ->method('deleteInstanceAsync')
            ->with('inst1', false, $headers)
            ->willReturn($asyncDelete);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(6))
            ->method('instance')
            ->willReturn($instanceClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new InstanceManager($factory);

        $manager->createInstance($config, $headers);
        $this->assertSame($asyncCreate, $manager->createInstanceAsync($config, $headers));
        $manager->updateInstance('inst1', $reconfig, $headers);
        $this->assertSame($asyncUpdate, $manager->updateInstanceAsync('inst1', $reconfig, $headers));
        $this->assertSame($challenge, $manager->deleteInstance('inst1', true, $headers));
        $this->assertSame($asyncDelete, $manager->deleteInstanceAsync('inst1', false, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_access_token_endpoints_to_the_sdk_instance_client(): void
    {
        $headers = ['X-Test' => 'tokens'];
        $login = new LoginTokenRequest(scope: 'readonly');
        $listRequest = new GetAccessTokensRequest(limit: 10, offset: 0);
        $tokenPayload = ['access_token' => 'x'];
        $listPayload = ['tokens' => []];
        $asyncLogin = new stdClass();
        $asyncList = new stdClass();
        $asyncDelete = new stdClass();
        $asyncDeleteSerial = new stdClass();

        $instanceClient = $this->createMock(InstanceClient::class);
        $instanceClient->expects($this->once())
            ->method('getAccessToken')
            ->with('default', $login, $headers)
            ->willReturn($tokenPayload);
        $instanceClient->expects($this->once())
            ->method('getAccessTokenAsync')
            ->with('default', $login, $headers)
            ->willReturn($asyncLogin);
        $instanceClient->expects($this->once())
            ->method('getAccessTokens')
            ->with('default', $listRequest, $headers)
            ->willReturn($listPayload);
        $instanceClient->expects($this->once())
            ->method('getAccessTokensAsync')
            ->with('default', null, $headers)
            ->willReturn($asyncList);
        $instanceClient->expects($this->once())
            ->method('deleteAccessToken')
            ->with('default', $headers);
        $instanceClient->expects($this->once())
            ->method('deleteAccessTokenAsync')
            ->with('default', $headers)
            ->willReturn($asyncDelete);
        $instanceClient->expects($this->once())
            ->method('deleteAccessTokenBySerial')
            ->with('default', 42, $headers);
        $instanceClient->expects($this->once())
            ->method('deleteAccessTokenBySerialAsync')
            ->with('default', 42, $headers)
            ->willReturn($asyncDeleteSerial);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(8))
            ->method('instance')
            ->willReturn($instanceClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new InstanceManager($factory);

        $this->assertSame($tokenPayload, $manager->getAccessToken('default', $login, $headers));
        $this->assertSame($asyncLogin, $manager->getAccessTokenAsync('default', $login, $headers));
        $this->assertSame($listPayload, $manager->getAccessTokens('default', $listRequest, $headers));
        $this->assertSame($asyncList, $manager->getAccessTokensAsync('default', null, $headers));
        $manager->deleteAccessToken('default', $headers);
        $this->assertSame($asyncDelete, $manager->deleteAccessTokenAsync('default', $headers));
        $manager->deleteAccessTokenBySerial('default', 42, $headers);
        $this->assertSame($asyncDeleteSerial, $manager->deleteAccessTokenBySerialAsync('default', 42, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_auth_endpoints_to_the_sdk_instance_client(): void
    {
        $headers = ['X-Test' => 'auth'];
        $auth = new InstanceAuthConfigToken(password: 'secret');
        $challenge = $this->createMock(ChallengeResponse::class);
        $asyncForgot = new stdClass();
        $asyncUpdate = new stdClass();

        $instanceClient = $this->createMock(InstanceClient::class);
        $instanceClient->expects($this->once())
            ->method('forgotPassword')
            ->with('default', $auth, $headers)
            ->willReturn($challenge);
        $instanceClient->expects($this->once())
            ->method('forgotPasswordAsync')
            ->with('default', $auth, $headers)
            ->willReturn($asyncForgot);
        $instanceClient->expects($this->once())
            ->method('updateAuth')
            ->with('default', $auth, $headers)
            ->willReturn($challenge);
        $instanceClient->expects($this->once())
            ->method('updateAuthAsync')
            ->with('default', $auth, $headers)
            ->willReturn($asyncUpdate);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(4))
            ->method('instance')
            ->willReturn($instanceClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new InstanceManager($factory);

        $this->assertSame($challenge, $manager->forgotPassword('default', $auth, $headers));
        $this->assertSame($asyncForgot, $manager->forgotPasswordAsync('default', $auth, $headers));
        $this->assertSame($challenge, $manager->updateAuth('default', $auth, $headers));
        $this->assertSame($asyncUpdate, $manager->updateAuthAsync('default', $auth, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_kyc_and_statistics_to_the_sdk_instance_client(): void
    {
        $headers = ['X-Test' => 'kyc-stats'];
        $kycRequest = new GetKycStatusRequest(lpt: 1);
        $amountRequest = new GetMerchantStatisticsAmountRequest(by: 'ANY');
        $counterRequest = new GetMerchantStatisticsCounterRequest(by: 'BUCKET');
        $kyc = ['kyc' => 'ok'];
        $amount = ['amount' => []];
        $counter = ['counter' => []];
        $asyncKyc = new stdClass();
        $asyncAmount = new stdClass();
        $asyncCounter = new stdClass();

        $instanceClient = $this->createMock(InstanceClient::class);
        $instanceClient->expects($this->once())
            ->method('getKycStatus')
            ->with('default', $kycRequest, $headers)
            ->willReturn($kyc);
        $instanceClient->expects($this->once())
            ->method('getKycStatusAsync')
            ->with('default', null, $headers)
            ->willReturn($asyncKyc);
        $instanceClient->expects($this->once())
            ->method('getMerchantStatisticsAmount')
            ->with('default', 'slug-a', $amountRequest, $headers)
            ->willReturn($amount);
        $instanceClient->expects($this->once())
            ->method('getMerchantStatisticsAmountAsync')
            ->with('default', 'slug-a', null, $headers)
            ->willReturn($asyncAmount);
        $instanceClient->expects($this->once())
            ->method('getMerchantStatisticsCounter')
            ->with('default', 'slug-c', $counterRequest, $headers)
            ->willReturn($counter);
        $instanceClient->expects($this->once())
            ->method('getMerchantStatisticsCounterAsync')
            ->with('default', 'slug-c', null, $headers)
            ->willReturn($asyncCounter);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(6))
            ->method('instance')
            ->willReturn($instanceClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new InstanceManager($factory);

        $this->assertSame($kyc, $manager->getKycStatus('default', $kycRequest, $headers));
        $this->assertSame($asyncKyc, $manager->getKycStatusAsync('default', null, $headers));
        $this->assertSame($amount, $manager->getMerchantStatisticsAmount('default', 'slug-a', $amountRequest, $headers));
        $this->assertSame($asyncAmount, $manager->getMerchantStatisticsAmountAsync('default', 'slug-a', null, $headers));
        $this->assertSame($counter, $manager->getMerchantStatisticsCounter('default', 'slug-c', $counterRequest, $headers));
        $this->assertSame($asyncCounter, $manager->getMerchantStatisticsCounterAsync('default', 'slug-c', null, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    private function minimalInstanceConfigurationMessage(): InstanceConfigurationMessage
    {
        return InstanceConfigurationMessage::createFromArray([
            'id' => 'testmerchant',
            'name' => 'Test Merchant',
            'auth' => [
                'method' => 'token',
                'password' => 'secret-password',
            ],
            'address' => ['country' => 'DE'],
            'jurisdiction' => ['country' => 'DE'],
            'use_stefan' => false,
            'default_wire_transfer_delay' => ['d_us' => 0],
            'default_pay_delay' => ['d_us' => 0],
        ]);
    }

    private function minimalInstanceReconfigurationMessage(): InstanceReconfigurationMessage
    {
        return InstanceReconfigurationMessage::createFromArray([
            'name' => 'Renamed',
            'address' => ['country' => 'DE'],
            'jurisdiction' => ['country' => 'DE'],
            'use_stefan' => false,
            'default_wire_transfer_delay' => ['d_us' => 0],
            'default_pay_delay' => ['d_us' => 0],
        ]);
    }
}
