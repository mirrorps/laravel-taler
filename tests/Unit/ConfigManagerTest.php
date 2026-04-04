<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Config\ConfigManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeTalerClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Config\ConfigClient;
use Taler\Api\Config\Dto\ExchangeConfigInfo;
use Taler\Api\Config\Dto\MerchantVersionResponse;
use Taler\Api\Dto\CurrencySpecification;
use Taler\Taler as SdkTaler;

class ConfigManagerTest extends TestCase
{
    public function test_it_proxies_get_config_calls_to_the_sdk_config_client(): void
    {
        $headers = ['X-Test' => 'config'];
        $response = new MerchantVersionResponse(
            version: '21:0:0',
            name: 'taler-merchant',
            currency: 'EUR',
            currencies: [
                'EUR' => new CurrencySpecification(
                    name: 'Euro',
                    currency: 'EUR',
                    num_fractional_input_digits: 2,
                    num_fractional_normal_digits: 2,
                    num_fractional_trailing_zero_digits: 2,
                    alt_unit_names: ['0' => 'EUR'],
                ),
            ],
            exchanges: [
                new ExchangeConfigInfo(
                    base_url: 'https://exchange.example.test/',
                    currency: 'EUR',
                    master_pub: 'merchant-master-pub',
                ),
            ],
            have_self_provisioning: true,
            have_donau: false,
            implementation: 'urn:example:taler',
            mandatory_tan_channels: ['sms'],
        );
        $asyncResponse = new stdClass();

        $configClient = $this->createMock(ConfigClient::class);
        $configClient->expects($this->once())
            ->method('getConfig')
            ->with($headers)
            ->willReturn($response);
        $configClient->expects($this->once())
            ->method('getConfigAsync')
            ->with($headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('configApi')
            ->willReturn($configClient);

        $factory = new FakeTalerClientFactory($sdk);
        $manager = new ConfigManager($factory);

        $this->assertSame($response, $manager->getConfig($headers));
        $this->assertSame($asyncResponse, $manager->getConfigAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }
}
