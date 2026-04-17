<?php

namespace Mirrorps\LaravelTaler\Logging;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Resolves a PSR-3 logger for the Taler SDK using Laravel's logging system.
 *
 * The underlying `mirrorps/taler-php` SDK owns every decision about *what* and
 * *when* to log (debug-level request/response logging, error-level failure
 * logging, redaction of sensitive data, etc.). The only wrapper concern this
 * class is responsible for is *where* those log records end up:
 *
 *  - When `taler.logging_enabled` is false, a {@see NullLogger} is returned
 *    and Laravel's logging stack is bypassed entirely.
 *  - When `taler.log_channel` is a non-empty string, that channel is resolved
 *    via Laravel's {@see LogManager}.
 *  - Otherwise the application's default log channel is returned.
 */
class LogChannelResolver
{
    public function __construct(
        private LogManager $logManager,
        private Repository $config,
    ) {
    }

    /**
     * Resolve the logger instance the SDK should use.
     */
    public function resolve(): LoggerInterface
    {
        if (!$this->isLoggingEnabled()) {
            return new NullLogger();
        }

        $channel = $this->config->get('taler.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            return $this->logManager->channel(trim($channel));
        }

        return $this->logManager->channel();
    }

    private function isLoggingEnabled(): bool
    {
        return (bool) $this->config->get('taler.logging_enabled', true);
    }
}
