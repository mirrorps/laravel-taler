<?php

namespace Mirrorps\LaravelTaler\Templates;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Templates\Dto\TemplateAddDetails;
use Taler\Api\Templates\Dto\TemplateDetails;
use Taler\Api\Templates\Dto\TemplatePatchDetails;
use Taler\Api\Templates\Dto\TemplatesSummaryResponse;
use Taler\Api\Templates\TemplatesClient;
use Taler\Taler as SdkTaler;

class TemplatesManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): TemplatesClient
    {
        return $this->client()->templates();
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createTemplate(TemplateAddDetails $details, array $headers = []): void
    {
        $this->api()->createTemplate($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createTemplateAsync(TemplateAddDetails $details, array $headers = []): mixed
    {
        return $this->api()->createTemplateAsync($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateTemplate(string $templateId, TemplatePatchDetails $details, array $headers = []): void
    {
        $this->api()->updateTemplate($templateId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateTemplateAsync(string $templateId, TemplatePatchDetails $details, array $headers = []): mixed
    {
        return $this->api()->updateTemplateAsync($templateId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return TemplatesSummaryResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTemplates(array $headers = []): TemplatesSummaryResponse|array
    {
        return $this->api()->getTemplates($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTemplatesAsync(array $headers = []): mixed
    {
        return $this->api()->getTemplatesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return TemplateDetails|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTemplate(string $templateId, array $headers = []): TemplateDetails|array
    {
        return $this->api()->getTemplate($templateId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getTemplateAsync(string $templateId, array $headers = []): mixed
    {
        return $this->api()->getTemplateAsync($templateId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTemplate(string $templateId, array $headers = []): void
    {
        $this->api()->deleteTemplate($templateId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteTemplateAsync(string $templateId, array $headers = []): mixed
    {
        return $this->api()->deleteTemplateAsync($templateId, $headers);
    }
}
