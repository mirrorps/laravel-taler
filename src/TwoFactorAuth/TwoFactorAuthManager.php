<?php

namespace Mirrorps\LaravelTaler\TwoFactorAuth;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\TwoFactorAuth\Dto\ChallengeRequestResponse;
use Taler\Api\TwoFactorAuth\Dto\MerchantChallengeSolveRequest;
use Taler\Api\TwoFactorAuth\TwoFactorAuthClient;
use Taler\Taler as SdkTaler;

class TwoFactorAuthManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): TwoFactorAuthClient
    {
        return $this->client()->twoFactorAuth();
    }

    /**
     * @param array<string, mixed>|null $requestBody
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function requestChallenge(
        string $instanceId,
        string $challengeId,
        ?array $requestBody = null,
        array $headers = []
    ): ChallengeRequestResponse {
        return $this->api()->requestChallenge($instanceId, $challengeId, $requestBody, $headers);
    }

    /**
     * @param array<string, mixed>|null $requestBody
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function requestChallengeAsync(
        string $instanceId,
        string $challengeId,
        ?array $requestBody = null,
        array $headers = []
    ): mixed {
        return $this->api()->requestChallengeAsync($instanceId, $challengeId, $requestBody, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function confirmChallenge(
        string $instanceId,
        string $challengeId,
        MerchantChallengeSolveRequest $requestBody,
        array $headers = []
    ): void {
        $this->api()->confirmChallenge($instanceId, $challengeId, $requestBody, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function confirmChallengeAsync(
        string $instanceId,
        string $challengeId,
        MerchantChallengeSolveRequest $requestBody,
        array $headers = []
    ): mixed {
        return $this->api()->confirmChallengeAsync($instanceId, $challengeId, $requestBody, $headers);
    }
}
