<?php

namespace Mirrorps\LaravelTaler\Webhooks;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Webhooks\Dto\WebhookAddDetails;
use Taler\Api\Webhooks\Dto\WebhookDetails;
use Taler\Api\Webhooks\Dto\WebhookPatchDetails;
use Taler\Api\Webhooks\Dto\WebhookSummaryResponse;
use Taler\Api\Webhooks\WebhooksClient;
use Taler\Taler as SdkTaler;

class WebhooksManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): WebhooksClient
    {
        return $this->client()->webhooks();
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createWebhook(WebhookAddDetails $details, array $headers = []): void
    {
        $this->api()->createWebhook($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createWebhookAsync(WebhookAddDetails $details, array $headers = []): mixed
    {
        return $this->api()->createWebhookAsync($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateWebhook(string $webhookId, WebhookPatchDetails $details, array $headers = []): void
    {
        $this->api()->updateWebhook($webhookId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateWebhookAsync(string $webhookId, WebhookPatchDetails $details, array $headers = []): mixed
    {
        return $this->api()->updateWebhookAsync($webhookId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return WebhookSummaryResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getWebhooks(array $headers = []): WebhookSummaryResponse|array
    {
        return $this->api()->getWebhooks($headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function getWebhooksAsync(array $headers = []): mixed
    {
        return $this->api()->getWebhooksAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return WebhookDetails|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getWebhook(string $webhookId, array $headers = []): WebhookDetails|array
    {
        return $this->api()->getWebhook($webhookId, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function getWebhookAsync(string $webhookId, array $headers = []): mixed
    {
        return $this->api()->getWebhookAsync($webhookId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteWebhook(string $webhookId, array $headers = []): void
    {
        $this->api()->deleteWebhook($webhookId, $headers);
    }

    /**
     * @param array<string, string> $headers
     */
    public function deleteWebhookAsync(string $webhookId, array $headers = []): mixed
    {
        return $this->api()->deleteWebhookAsync($webhookId, $headers);
    }
}
