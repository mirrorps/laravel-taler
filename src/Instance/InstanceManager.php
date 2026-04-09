<?php

namespace Mirrorps\LaravelTaler\Instance;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Instance\Dto\GetAccessTokensRequest;
use Taler\Api\Instance\Dto\GetKycStatusRequest;
use Taler\Api\Instance\Dto\GetMerchantStatisticsAmountRequest;
use Taler\Api\Instance\Dto\GetMerchantStatisticsCounterRequest;
use Taler\Api\Instance\Dto\InstanceAuthConfigExternal;
use Taler\Api\Instance\Dto\InstanceAuthConfigToken;
use Taler\Api\Instance\Dto\InstanceAuthConfigTokenOLD;
use Taler\Api\Instance\Dto\InstanceConfigurationMessage;
use Taler\Api\Instance\Dto\InstanceReconfigurationMessage;
use Taler\Api\Instance\Dto\InstancesResponse;
use Taler\Api\Instance\Dto\LoginTokenRequest;
use Taler\Api\Instance\Dto\LoginTokenSuccessResponse;
use Taler\Api\Instance\Dto\MerchantAccountKycRedirectsResponse;
use Taler\Api\Instance\Dto\MerchantStatisticsAmountResponse;
use Taler\Api\Instance\Dto\MerchantStatisticsCounterResponse;
use Taler\Api\Instance\Dto\QueryInstancesResponse;
use Taler\Api\Instance\Dto\TokenInfos;
use Taler\Api\Instance\InstanceClient;
use Taler\Api\TwoFactorAuth\Dto\ChallengeResponse;
use Taler\Taler as SdkTaler;

class InstanceManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): InstanceClient
    {
        return $this->client()->instance();
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createInstance(InstanceConfigurationMessage $instanceConfiguration, array $headers = []): void
    {
        $this->api()->createInstance($instanceConfiguration, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createInstanceAsync(InstanceConfigurationMessage $instanceConfiguration, array $headers = []): mixed
    {
        return $this->api()->createInstanceAsync($instanceConfiguration, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function forgotPassword(
        string $instanceId,
        InstanceAuthConfigToken|InstanceAuthConfigTokenOLD|InstanceAuthConfigExternal $authConfig,
        array $headers = []
    ): ?ChallengeResponse {
        return $this->api()->forgotPassword($instanceId, $authConfig, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function forgotPasswordAsync(
        string $instanceId,
        InstanceAuthConfigToken|InstanceAuthConfigTokenOLD|InstanceAuthConfigExternal $authConfig,
        array $headers = []
    ): mixed {
        return $this->api()->forgotPasswordAsync($instanceId, $authConfig, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateAuth(
        string $instanceId,
        InstanceAuthConfigToken|InstanceAuthConfigTokenOLD|InstanceAuthConfigExternal $authConfig,
        array $headers = []
    ): ?ChallengeResponse {
        return $this->api()->updateAuth($instanceId, $authConfig, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateAuthAsync(
        string $instanceId,
        InstanceAuthConfigToken|InstanceAuthConfigTokenOLD|InstanceAuthConfigExternal $authConfig,
        array $headers = []
    ): mixed {
        return $this->api()->updateAuthAsync($instanceId, $authConfig, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return LoginTokenSuccessResponse|ChallengeResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccessToken(
        string $instanceId,
        LoginTokenRequest $loginTokenRequest,
        array $headers = []
    ): LoginTokenSuccessResponse|ChallengeResponse|array {
        return $this->api()->getAccessToken($instanceId, $loginTokenRequest, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccessTokenAsync(
        string $instanceId,
        LoginTokenRequest $loginTokenRequest,
        array $headers = []
    ): mixed {
        return $this->api()->getAccessTokenAsync($instanceId, $loginTokenRequest, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return TokenInfos|array<string, mixed>|null
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccessTokens(
        string $instanceId,
        ?GetAccessTokensRequest $request = null,
        array $headers = []
    ): TokenInfos|array|null {
        return $this->api()->getAccessTokens($instanceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getAccessTokensAsync(
        string $instanceId,
        ?GetAccessTokensRequest $request = null,
        array $headers = []
    ): mixed {
        return $this->api()->getAccessTokensAsync($instanceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return MerchantAccountKycRedirectsResponse|array<string, mixed>|null
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getKycStatus(
        string $instanceId,
        ?GetKycStatusRequest $request = null,
        array $headers = []
    ): MerchantAccountKycRedirectsResponse|array|null {
        return $this->api()->getKycStatus($instanceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getKycStatusAsync(
        string $instanceId,
        ?GetKycStatusRequest $request = null,
        array $headers = []
    ): mixed {
        return $this->api()->getKycStatusAsync($instanceId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccessToken(string $instanceId, array $headers = []): void
    {
        $this->api()->deleteAccessToken($instanceId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccessTokenAsync(string $instanceId, array $headers = []): mixed
    {
        return $this->api()->deleteAccessTokenAsync($instanceId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccessTokenBySerial(string $instanceId, int $serial, array $headers = []): void
    {
        $this->api()->deleteAccessTokenBySerial($instanceId, $serial, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteAccessTokenBySerialAsync(string $instanceId, int $serial, array $headers = []): mixed
    {
        return $this->api()->deleteAccessTokenBySerialAsync($instanceId, $serial, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateInstance(
        string $instanceId,
        InstanceReconfigurationMessage $message,
        array $headers = []
    ): void {
        $this->api()->updateInstance($instanceId, $message, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateInstanceAsync(
        string $instanceId,
        InstanceReconfigurationMessage $message,
        array $headers = []
    ): mixed {
        return $this->api()->updateInstanceAsync($instanceId, $message, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return InstancesResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstances(array $headers = []): InstancesResponse|array
    {
        return $this->api()->getInstances($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstancesAsync(array $headers = []): mixed
    {
        return $this->api()->getInstancesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return QueryInstancesResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstance(string $instanceId, array $headers = []): QueryInstancesResponse|array
    {
        return $this->api()->getInstance($instanceId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getInstanceAsync(string $instanceId, array $headers = []): mixed
    {
        return $this->api()->getInstanceAsync($instanceId, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return MerchantStatisticsAmountResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getMerchantStatisticsAmount(
        string $instanceId,
        string $slug,
        ?GetMerchantStatisticsAmountRequest $request = null,
        array $headers = []
    ): MerchantStatisticsAmountResponse|array {
        return $this->api()->getMerchantStatisticsAmount($instanceId, $slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getMerchantStatisticsAmountAsync(
        string $instanceId,
        string $slug,
        ?GetMerchantStatisticsAmountRequest $request = null,
        array $headers = []
    ): mixed {
        return $this->api()->getMerchantStatisticsAmountAsync($instanceId, $slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return MerchantStatisticsCounterResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getMerchantStatisticsCounter(
        string $instanceId,
        string $slug,
        ?GetMerchantStatisticsCounterRequest $request = null,
        array $headers = []
    ): MerchantStatisticsCounterResponse|array {
        return $this->api()->getMerchantStatisticsCounter($instanceId, $slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getMerchantStatisticsCounterAsync(
        string $instanceId,
        string $slug,
        ?GetMerchantStatisticsCounterRequest $request = null,
        array $headers = []
    ): mixed {
        return $this->api()->getMerchantStatisticsCounterAsync($instanceId, $slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteInstance(string $instanceId, bool $purge = false, array $headers = []): ?ChallengeResponse
    {
        return $this->api()->deleteInstance($instanceId, $purge, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteInstanceAsync(string $instanceId, bool $purge = false, array $headers = []): mixed
    {
        return $this->api()->deleteInstanceAsync($instanceId, $purge, $headers);
    }
}
