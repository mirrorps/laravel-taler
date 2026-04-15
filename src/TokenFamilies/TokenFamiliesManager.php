<?php

namespace Mirrorps\LaravelTaler\TokenFamilies;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\TokenFamilies\Dto\TokenFamiliesList;
use Taler\Api\TokenFamilies\Dto\TokenFamilyCreateRequest;
use Taler\Api\TokenFamilies\Dto\TokenFamilyDetails;
use Taler\Api\TokenFamilies\Dto\TokenFamilyUpdateRequest;
use Taler\Api\TokenFamilies\TokenFamiliesClient;
use Taler\Taler as SdkTaler;

class TokenFamiliesManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): TokenFamiliesClient
    {
        return $this->client()->tokenFamilies();
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createTokenFamily(TokenFamilyCreateRequest $request, array $headers = []): void
    {
        $this->api()->createTokenFamily($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createTokenFamilyAsync(TokenFamilyCreateRequest $request, array $headers = []): mixed
    {
        return $this->api()->createTokenFamilyAsync($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateTokenFamily(string $slug, TokenFamilyUpdateRequest $request, array $headers = []): void
    {
        $this->api()->updateTokenFamily($slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateTokenFamilyAsync(string $slug, TokenFamilyUpdateRequest $request, array $headers = []): mixed
    {
        return $this->api()->updateTokenFamilyAsync($slug, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return TokenFamiliesList|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTokenFamilies(array $headers = []): TokenFamiliesList|array
    {
        return $this->api()->getTokenFamilies($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTokenFamiliesAsync(array $headers = []): mixed
    {
        return $this->api()->getTokenFamiliesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return TokenFamilyDetails|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTokenFamily(string $slug, array $headers = []): TokenFamilyDetails|array
    {
        return $this->api()->getTokenFamily($slug, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTokenFamilyAsync(string $slug, array $headers = []): mixed
    {
        return $this->api()->getTokenFamilyAsync($slug, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTokenFamily(string $slug, array $headers = []): void
    {
        $this->api()->deleteTokenFamily($slug, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTokenFamilyAsync(string $slug, array $headers = []): mixed
    {
        return $this->api()->deleteTokenFamilyAsync($slug, $headers);
    }
}
