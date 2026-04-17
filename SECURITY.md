# Security Policy

## Supported Scope

This package is a Laravel integration layer for [`mirrorps/taler-php`](https://github.com/mirrorps/taler-php).

Security-sensitive behavior is provided by the upstream SDK, including:

- request construction and transport behavior
- authentication and token handling
- endpoint validation
- secret redaction in SDK debug logging

This package is responsible for Laravel-specific integration concerns, including:

- configuration mapping from Laravel config into SDK options
- service-container bindings and facade access
- package defaults such as `scope=readonly` and `debug_logging_enabled=false`

## Reporting A Vulnerability

Please do not open public issues for suspected security vulnerabilities.

Use GitHub's private vulnerability reporting for this repository and include:

- a clear description of the issue
- affected package version(s)
- steps to reproduce
- impact assessment
- any suggested remediation

## Security Notes

- Keep `TALER_DEBUG_LOGGING_ENABLED=false` in production unless you have verified that your full logging path is appropriate for production use.
