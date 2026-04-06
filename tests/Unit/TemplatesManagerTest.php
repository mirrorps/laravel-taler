<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Templates\TemplatesManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Dto\RelativeTime;
use Taler\Api\Templates\Dto\TemplateAddDetails;
use Taler\Api\Templates\Dto\TemplateContractDetails;
use Taler\Api\Templates\Dto\TemplateDetails;
use Taler\Api\Templates\Dto\TemplatePatchDetails;
use Taler\Api\Templates\Dto\TemplatesSummaryResponse;
use Taler\Api\Templates\TemplatesClient;
use Taler\Taler as SdkTaler;

class TemplatesManagerTest extends TestCase
{
    public function test_it_proxies_get_templates_calls_to_the_sdk_templates_client(): void
    {
        $headers = ['X-Test' => 'templates'];
        $summary = new TemplatesSummaryResponse([]);
        $asyncResponse = new stdClass();

        $templatesClient = $this->createMock(TemplatesClient::class);
        $templatesClient->expects($this->once())
            ->method('getTemplates')
            ->with($headers)
            ->willReturn($summary);
        $templatesClient->expects($this->once())
            ->method('getTemplatesAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('templates')
            ->willReturn($templatesClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TemplatesManager($factory);

        $this->assertSame($summary, $manager->getTemplates($headers));
        $this->assertSame($asyncResponse, $manager->getTemplatesAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_get_template_calls_to_the_sdk_templates_client(): void
    {
        $templateId = 'tpl-1';
        $headers = ['X-Test' => 'template'];
        $details = $this->createMock(TemplateDetails::class);
        $asyncResponse = new stdClass();

        $templatesClient = $this->createMock(TemplatesClient::class);
        $templatesClient->expects($this->once())
            ->method('getTemplate')
            ->with($templateId, $headers)
            ->willReturn($details);
        $templatesClient->expects($this->once())
            ->method('getTemplateAsync')
            ->with($templateId, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('templates')
            ->willReturn($templatesClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TemplatesManager($factory);

        $this->assertSame($details, $manager->getTemplate($templateId, $headers));
        $this->assertSame($asyncResponse, $manager->getTemplateAsync($templateId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_create_template_calls_to_the_sdk_templates_client(): void
    {
        $details = $this->makeTemplateAddDetails();
        $headers = ['X-Test' => 'create'];
        $asyncResponse = new stdClass();

        $templatesClient = $this->createMock(TemplatesClient::class);
        $templatesClient->expects($this->once())
            ->method('createTemplate')
            ->with($details, $headers);
        $templatesClient->expects($this->once())
            ->method('createTemplateAsync')
            ->with($details, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('templates')
            ->willReturn($templatesClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TemplatesManager($factory);

        $manager->createTemplate($details, $headers);

        $this->assertSame($asyncResponse, $manager->createTemplateAsync($details, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_update_template_calls_to_the_sdk_templates_client(): void
    {
        $templateId = 'tpl-1';
        $details = $this->makeTemplatePatchDetails();
        $headers = ['X-Test' => 'update'];
        $asyncResponse = new stdClass();

        $templatesClient = $this->createMock(TemplatesClient::class);
        $templatesClient->expects($this->once())
            ->method('updateTemplate')
            ->with($templateId, $details, $headers);
        $templatesClient->expects($this->once())
            ->method('updateTemplateAsync')
            ->with($templateId, $details, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('templates')
            ->willReturn($templatesClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TemplatesManager($factory);

        $manager->updateTemplate($templateId, $details, $headers);

        $this->assertSame($asyncResponse, $manager->updateTemplateAsync($templateId, $details, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_delete_template_calls_to_the_sdk_templates_client(): void
    {
        $templateId = 'tpl-1';
        $headers = ['X-Test' => 'delete'];
        $asyncResponse = new stdClass();

        $templatesClient = $this->createMock(TemplatesClient::class);
        $templatesClient->expects($this->once())
            ->method('deleteTemplate')
            ->with($templateId, $headers);
        $templatesClient->expects($this->once())
            ->method('deleteTemplateAsync')
            ->with($templateId, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('templates')
            ->willReturn($templatesClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new TemplatesManager($factory);

        $manager->deleteTemplate($templateId, $headers);

        $this->assertSame($asyncResponse, $manager->deleteTemplateAsync($templateId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    private function makeTemplateContract(): TemplateContractDetails
    {
        return new TemplateContractDetails(
            minimum_age: 0,
            pay_duration: new RelativeTime(d_us: 3600000000),
            summary: 'Test template',
            currency: 'EUR',
            amount: 'EUR:10.00',
        );
    }

    private function makeTemplateAddDetails(): TemplateAddDetails
    {
        return new TemplateAddDetails(
            template_id: 'my-template',
            template_description: 'My template',
            template_contract: $this->makeTemplateContract(),
        );
    }

    private function makeTemplatePatchDetails(): TemplatePatchDetails
    {
        return new TemplatePatchDetails(
            template_description: 'Updated',
            template_contract: $this->makeTemplateContract(),
        );
    }
}
