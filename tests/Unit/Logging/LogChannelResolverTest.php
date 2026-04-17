<?php

namespace Mirrorps\LaravelTaler\Tests\Unit\Logging;

use Illuminate\Log\LogManager;
use Mirrorps\LaravelTaler\Logging\LogChannelResolver;
use Mirrorps\LaravelTaler\Tests\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LogChannelResolverTest extends TestCase
{
    public function test_it_returns_the_default_channel_when_none_is_configured(): void
    {
        config()->set('taler.logging_enabled', true);
        config()->set('taler.log_channel', null);

        $resolver = $this->app->make(LogChannelResolver::class);

        $logger = $resolver->resolve();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertNotInstanceOf(NullLogger::class, $logger);

        /** @var LogManager $logManager */
        $logManager = $this->app->make('log');
        $this->assertSame($logManager->channel(), $logger);
    }

    public function test_it_returns_the_default_channel_when_value_is_whitespace(): void
    {
        config()->set('taler.logging_enabled', true);
        config()->set('taler.log_channel', '   ');

        $logger = $this->app->make(LogChannelResolver::class)->resolve();

        $this->assertNotInstanceOf(NullLogger::class, $logger);

        /** @var LogManager $logManager */
        $logManager = $this->app->make('log');
        $this->assertSame($logManager->channel(), $logger);
    }

    public function test_it_resolves_a_specific_log_channel_when_configured(): void
    {
        config()->set('logging.channels.taler', [
            'driver' => 'single',
            'path' => storage_path('logs/taler.log'),
            'level' => 'debug',
        ]);
        config()->set('taler.logging_enabled', true);
        config()->set('taler.log_channel', 'taler');

        $logger = $this->app->make(LogChannelResolver::class)->resolve();

        $this->assertNotInstanceOf(NullLogger::class, $logger);

        /** @var LogManager $logManager */
        $logManager = $this->app->make('log');
        $this->assertSame($logManager->channel('taler'), $logger);
    }

    public function test_it_returns_a_null_logger_when_logging_is_disabled(): void
    {
        config()->set('taler.logging_enabled', false);
        config()->set('taler.log_channel', 'taler');

        $logger = $this->app->make(LogChannelResolver::class)->resolve();

        $this->assertInstanceOf(NullLogger::class, $logger);
    }

    public function test_logging_is_enabled_by_default(): void
    {
        $logger = $this->app->make(LogChannelResolver::class)->resolve();

        $this->assertNotInstanceOf(NullLogger::class, $logger);
    }
}
