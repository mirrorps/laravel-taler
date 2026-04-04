<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Mirrorps\LaravelTaler\TwoFactorAuth\TwoFactorAuthManager;
use stdClass;
use Taler\Api\Dto\Timestamp;
use Taler\Api\TwoFactorAuth\Dto\ChallengeRequestResponse;
use Taler\Api\TwoFactorAuth\Dto\MerchantChallengeSolveRequest;
use Taler\Api\TwoFactorAuth\TwoFactorAuthClient;
use Taler\Taler as SdkTaler;

class TwoFactorAuthManagerTest extends TestCase
{
    public function test_it_proxies_request_challenge_calls_to_the_sdk_two_factor_auth_client(): void
    {
        $instanceId = 'default';
        $challengeId = 'challenge-uuid';
        $requestBody = ['channel' => 'email'];
        $headers = ['X-Test' => '2fa'];
        $response = new ChallengeRequestResponse(
            solve_expiration: new Timestamp(t_s: 1700000000),
            earliest_retransmission: new Timestamp(t_s: 1700000060),
        );
        $asyncResponse = new stdClass();

        $twoFactorAuthClient = $this->createMock(TwoFactorAuthClient::class);
        $twoFactorAuthClient->expects($this->once())
            ->method('requestChallenge')
            ->with($instanceId, $challengeId, $requestBody, $headers)
            ->willReturn($response);
        $twoFactorAuthClient->expects($this->once())
            ->method('requestChallengeAsync')
            ->with($instanceId, $challengeId, $requestBody, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('twoFactorAuth')
            ->willReturn($twoFactorAuthClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TwoFactorAuthManager($factory);

        $this->assertSame($response, $manager->requestChallenge($instanceId, $challengeId, $requestBody, $headers));
        $this->assertSame($asyncResponse, $manager->requestChallengeAsync($instanceId, $challengeId, $requestBody, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_confirm_challenge_calls_to_the_sdk_two_factor_auth_client(): void
    {
        $instanceId = 'default';
        $challengeId = 'challenge-uuid';
        $solveRequest = new MerchantChallengeSolveRequest(tan: '123456');
        $headers = ['X-Test' => '2fa-confirm'];
        $asyncResponse = new stdClass();

        $twoFactorAuthClient = $this->createMock(TwoFactorAuthClient::class);
        $twoFactorAuthClient->expects($this->once())
            ->method('confirmChallenge')
            ->with($instanceId, $challengeId, $solveRequest, $headers);
        $twoFactorAuthClient->expects($this->once())
            ->method('confirmChallengeAsync')
            ->with($instanceId, $challengeId, $solveRequest, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('twoFactorAuth')
            ->willReturn($twoFactorAuthClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TwoFactorAuthManager($factory);

        $manager->confirmChallenge($instanceId, $challengeId, $solveRequest, $headers);

        $this->assertSame($asyncResponse, $manager->confirmChallengeAsync($instanceId, $challengeId, $solveRequest, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
