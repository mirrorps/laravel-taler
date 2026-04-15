<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Tests\Fakes\FakeTokenFamiliesClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Mirrorps\LaravelTaler\TokenFamilies\TokenFamiliesManager;
use stdClass;
use Taler\Api\Dto\RelativeTime;
use Taler\Api\Dto\Timestamp;
use Taler\Api\TokenFamilies\Dto\TokenFamiliesList;
use Taler\Api\TokenFamilies\Dto\TokenFamilyCreateRequest;
use Taler\Api\TokenFamilies\Dto\TokenFamilyDetails;
use Taler\Api\TokenFamilies\Dto\TokenFamilyUpdateRequest;
use Taler\Api\TokenFamilies\TokenFamiliesClient;
use Taler\Taler as SdkTaler;

class TokenFamiliesManagerTest extends TestCase
{
    public function test_it_proxies_get_token_families_calls_to_the_sdk_client(): void
    {
        $headers = ['X-Test' => 'list'];
        $list = TokenFamiliesList::createFromArray(['token_families' => []]);
        $asyncResponse = new stdClass();

        $api = $this->createMock(TokenFamiliesClient::class);
        $api->expects($this->once())
            ->method('getTokenFamilies')
            ->with($headers)
            ->willReturn($list);
        $api->expects($this->once())
            ->method('getTokenFamiliesAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('tokenFamilies')
            ->willReturn($api);

        $factory = new FakeTokenFamiliesClientFactory($sdk);
        $manager = new TokenFamiliesManager($factory);

        $this->assertSame($list, $manager->getTokenFamilies($headers));
        $this->assertSame($asyncResponse, $manager->getTokenFamiliesAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_get_token_family_calls_to_the_sdk_client(): void
    {
        $slug = 'family-01';
        $headers = ['X-Test' => 'show'];
        $details = $this->makeTokenFamilyDetails();
        $asyncResponse = new stdClass();

        $api = $this->createMock(TokenFamiliesClient::class);
        $api->expects($this->once())
            ->method('getTokenFamily')
            ->with($slug, $headers)
            ->willReturn($details);
        $api->expects($this->once())
            ->method('getTokenFamilyAsync')
            ->with($slug, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('tokenFamilies')
            ->willReturn($api);

        $factory = new FakeTokenFamiliesClientFactory($sdk);
        $manager = new TokenFamiliesManager($factory);

        $this->assertSame($details, $manager->getTokenFamily($slug, $headers));
        $this->assertSame($asyncResponse, $manager->getTokenFamilyAsync($slug, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_token_family_calls_to_the_sdk_client(): void
    {
        $request = $this->makeTokenFamilyCreateRequest();
        $headers = ['X-Test' => 'create'];
        $asyncResponse = new stdClass();

        $api = $this->createMock(TokenFamiliesClient::class);
        $api->expects($this->once())
            ->method('createTokenFamily')
            ->with($request, $headers);
        $api->expects($this->once())
            ->method('createTokenFamilyAsync')
            ->with($request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('tokenFamilies')
            ->willReturn($api);

        $factory = new FakeTokenFamiliesClientFactory($sdk);
        $manager = new TokenFamiliesManager($factory);

        $manager->createTokenFamily($request, $headers);

        $this->assertSame($asyncResponse, $manager->createTokenFamilyAsync($request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_update_token_family_calls_to_the_sdk_client(): void
    {
        $slug = 'family-01';
        $request = $this->makeTokenFamilyUpdateRequest();
        $headers = ['X-Test' => 'update'];
        $asyncResponse = new stdClass();

        $api = $this->createMock(TokenFamiliesClient::class);
        $api->expects($this->once())
            ->method('updateTokenFamily')
            ->with($slug, $request, $headers);
        $api->expects($this->once())
            ->method('updateTokenFamilyAsync')
            ->with($slug, $request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('tokenFamilies')
            ->willReturn($api);

        $factory = new FakeTokenFamiliesClientFactory($sdk);
        $manager = new TokenFamiliesManager($factory);

        $manager->updateTokenFamily($slug, $request, $headers);

        $this->assertSame($asyncResponse, $manager->updateTokenFamilyAsync($slug, $request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_token_family_calls_to_the_sdk_client(): void
    {
        $slug = 'family-01';
        $headers = ['X-Test' => 'delete'];
        $asyncResponse = new stdClass();

        $api = $this->createMock(TokenFamiliesClient::class);
        $api->expects($this->once())
            ->method('deleteTokenFamily')
            ->with($slug, $headers);
        $api->expects($this->once())
            ->method('deleteTokenFamilyAsync')
            ->with($slug, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('tokenFamilies')
            ->willReturn($api);

        $factory = new FakeTokenFamiliesClientFactory($sdk);
        $manager = new TokenFamiliesManager($factory);

        $manager->deleteTokenFamily($slug, $headers);

        $this->assertSame($asyncResponse, $manager->deleteTokenFamilyAsync($slug, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    private function makeTokenFamilyCreateRequest(): TokenFamilyCreateRequest
    {
        return new TokenFamilyCreateRequest(
            slug: 'test-family',
            name: 'Test',
            description: 'Desc',
            valid_before: new Timestamp(t_s: 2000000000),
            duration: new RelativeTime(d_us: 3600000000),
            validity_granularity: new RelativeTime(d_us: 60000000),
            start_offset: new RelativeTime(d_us: 0),
            kind: 'discount',
        );
    }

    private function makeTokenFamilyUpdateRequest(): TokenFamilyUpdateRequest
    {
        return new TokenFamilyUpdateRequest(
            name: 'Updated',
            description: 'Updated desc',
            valid_after: new Timestamp(t_s: 1000000000),
            valid_before: new Timestamp(t_s: 2000000000),
        );
    }

    private function makeTokenFamilyDetails(): TokenFamilyDetails
    {
        return TokenFamilyDetails::createFromArray([
            'slug' => 'family-01',
            'name' => 'Family',
            'description' => 'Details',
            'valid_after' => ['t_s' => 1000000000],
            'valid_before' => ['t_s' => 2000000000],
            'duration' => ['d_us' => 3600000000],
            'validity_granularity' => ['d_us' => 60000000],
            'start_offset' => ['d_us' => 0],
            'kind' => 'discount',
            'issued' => 0,
            'used' => 0,
        ]);
    }
}
