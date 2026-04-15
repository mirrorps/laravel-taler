<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Mirrorps\LaravelTaler\Webhooks\WebhooksManager;
use stdClass;
use Taler\Api\Dto\Url;
use Taler\Api\Webhooks\Dto\WebhookAddDetails;
use Taler\Api\Webhooks\Dto\WebhookDetails;
use Taler\Api\Webhooks\Dto\WebhookEntry;
use Taler\Api\Webhooks\Dto\WebhookPatchDetails;
use Taler\Api\Webhooks\Dto\WebhookSummaryResponse;
use Taler\Api\Webhooks\WebhooksClient;
use Taler\Taler as SdkTaler;

class WebhooksManagerTest extends TestCase
{
    public function test_it_proxies_get_webhooks_calls_to_the_sdk_webhooks_client(): void
    {
        $headers = ['X-Test' => 'webhooks'];
        $response = new WebhookSummaryResponse([
            new WebhookEntry(webhook_id: 'wh-1', event_type: 'order.paid'),
        ]);
        $asyncResponse = new stdClass();

        $webhooksClient = $this->createMock(WebhooksClient::class);
        $webhooksClient->expects($this->once())
            ->method('getWebhooks')
            ->with($headers)
            ->willReturn($response);
        $webhooksClient->expects($this->once())
            ->method('getWebhooksAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('webhooks')
            ->willReturn($webhooksClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WebhooksManager($factory);

        $this->assertSame($response, $manager->getWebhooks($headers));
        $this->assertSame($asyncResponse, $manager->getWebhooksAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_get_webhook_calls_to_the_sdk_webhooks_client(): void
    {
        $webhookId = 'wh-1';
        $headers = ['X-Test' => 'webhook'];
        $response = new WebhookDetails(
            event_type: 'order.paid',
            url: 'https://example.com/hook',
            http_method: 'POST',
        );
        $asyncResponse = new stdClass();

        $webhooksClient = $this->createMock(WebhooksClient::class);
        $webhooksClient->expects($this->once())
            ->method('getWebhook')
            ->with($webhookId, $headers)
            ->willReturn($response);
        $webhooksClient->expects($this->once())
            ->method('getWebhookAsync')
            ->with($webhookId, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('webhooks')
            ->willReturn($webhooksClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WebhooksManager($factory);

        $this->assertSame($response, $manager->getWebhook($webhookId, $headers));
        $this->assertSame($asyncResponse, $manager->getWebhookAsync($webhookId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_webhook_calls_to_the_sdk_webhooks_client(): void
    {
        $request = new WebhookAddDetails(
            webhook_id: 'wh-1',
            event_type: 'order.paid',
            url: Url::fromString('https://example.com/webhook'),
            http_method: 'POST',
        );
        $headers = ['X-Test' => 'create-webhook'];
        $asyncResponse = new stdClass();

        $webhooksClient = $this->createMock(WebhooksClient::class);
        $webhooksClient->expects($this->once())
            ->method('createWebhook')
            ->with($request, $headers);
        $webhooksClient->expects($this->once())
            ->method('createWebhookAsync')
            ->with($request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('webhooks')
            ->willReturn($webhooksClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WebhooksManager($factory);

        $manager->createWebhook($request, $headers);

        $this->assertSame($asyncResponse, $manager->createWebhookAsync($request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_update_webhook_calls_to_the_sdk_webhooks_client(): void
    {
        $webhookId = 'wh-1';
        $request = new WebhookPatchDetails(
            event_type: 'order.refunded',
            url: Url::fromString('https://example.com/webhook/v2'),
            http_method: 'PUT',
        );
        $headers = ['X-Test' => 'update-webhook'];
        $asyncResponse = new stdClass();

        $webhooksClient = $this->createMock(WebhooksClient::class);
        $webhooksClient->expects($this->once())
            ->method('updateWebhook')
            ->with($webhookId, $request, $headers);
        $webhooksClient->expects($this->once())
            ->method('updateWebhookAsync')
            ->with($webhookId, $request, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('webhooks')
            ->willReturn($webhooksClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WebhooksManager($factory);

        $manager->updateWebhook($webhookId, $request, $headers);

        $this->assertSame($asyncResponse, $manager->updateWebhookAsync($webhookId, $request, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_webhook_calls_to_the_sdk_webhooks_client(): void
    {
        $webhookId = 'wh-1';
        $headers = ['X-Test' => 'delete-webhook'];
        $asyncResponse = new stdClass();

        $webhooksClient = $this->createMock(WebhooksClient::class);
        $webhooksClient->expects($this->once())
            ->method('deleteWebhook')
            ->with($webhookId, $headers);
        $webhooksClient->expects($this->once())
            ->method('deleteWebhookAsync')
            ->with($webhookId, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('webhooks')
            ->willReturn($webhooksClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new WebhooksManager($factory);

        $manager->deleteWebhook($webhookId, $headers);

        $this->assertSame($asyncResponse, $manager->deleteWebhookAsync($webhookId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
