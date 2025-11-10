# Bro World Core Bundle

Core utilities and cross‑cutting services for Bro World microservices, packaged as a reusable Symfony bundle.

> **Goals**
> - Centralize shared code (traits, helpers, subscribers, transport strategies, small infra services)
> - Keep domain‑specific logic inside each microservice
> - Provide clean, opt‑in configuration (no hard coupling)

---

## Requirements

- **PHP**: 8.3+
- **Symfony**: 7.2+
- **Optional**:
    - `ramsey/uuid` and `ramsey/uuid-doctrine` (if you use UUID value objects / DBAL types)
    - `symfony/messenger` (if you use the retry strategy included here)
    - `symfony/serializer`, `symfony/validator` (if you use provided helpers/subscribers relying on them)

> The bundle does **not** force `doctrine/orm` as a hard dependency. Install it in your apps as needed.

---

## Installation

### A) From Packagist (recommended)
```bash
composer require bro-world/core-bundle:^0.1
```

### B) From Git (VCS)
```bash
composer config repositories.bro-world-core vcs https://github.com/rami-aouinti/bro-world-core
composer require bro-world/core-bundle:^0.1
# Or, if you don’t have a tag yet:
composer require "bro-world/core-bundle:dev-master@dev"
```

### Enable the bundle
```php
// config/bundles.php
return [
    // ...
    Bro\WorldCoreBundle\BroWorldCoreBundle::class => ['all' => true],
];
```

---

## Configuration

Create `config/packages/bro_world_core.yaml` in your app. All options are **optional** and have sensible defaults.

```yaml
bro_world_core:
  default_locale: 'fr'             # default: 'fr'
  enable_feature_x: false          # default: false (example toggle)
  jwt_public_key: null             # e.g. '%env(resolve:JWT_PUBLIC_KEY_PATH)%'

  security:
    # Regex used by the LexikJwtAuthenticatorService to decide which paths are secured
    secured_path_regex: '^/api/(?!.*(security)|(test)|(doc)).*$' # default shown

  messenger:
    failed_retry:
      # Whether messages in the "failed" transport are retryable via messenger:failed:retry
      is_retryable: false
      # Waiting time (ms) between retries when using the custom RetryStrategy
      waiting_time: 0

  api_proxy:
    base_urls:
      media: '%env(resolve:MEDIA_API_BASE_URL)%'
    upload_defaults:
      # Form field that will receive the resolved context value when uploading files
      context_key_field: 'contextKey'
      # Default form values can be provided (each request may override them)
      workplace_id: '%env(default::WORKPLACE_ID)%'
      private: true
      extra_fields:
        locale: '%locale%'
```

> If you don’t need a given area (e.g. Messenger), you can omit it entirely.

---

## What’s included

Depending on what you use in your apps, this bundle typically provides:

- **Infrastructure / Services**
    - `LexikJwtAuthenticatorService` — small helper to decide if a request path is secured (uses `security.secured_path_regex`).
    - `Clock` — tiny time helper returning `DateTimeImmutable::now()`.

- **Event Subscribers**
    - `ExceptionSubscriber` — example subscriber that may need the current environment injected.

- **Messenger**
    - `Infrastructure\Messenger\Strategy\FailedRetry` — a configurable `RetryStrategyInterface` implementation.

- **Doctrine/Domain** (if you choose to use them)
    - Doctrine traits such as `Uuid`, `Timestampable`, `Blameable`.
    - Optional auto‑registration of Ramsey UUID types when `ramsey/uuid-doctrine` is installed.

> The exact set may evolve; keep an eye on the changelog.

---

## Service Wiring (what the bundle wires for you)

The bundle uses PHP config to autowire/autoconfigure its own namespace and binds common scalars so you don’t have to override them in each app, e.g.:

- `LexikJwtAuthenticatorService::$path` ⟶ `%bro_world_core.security.secured_path_regex%`
- `ExceptionSubscriber::$environment` ⟶ `%kernel.environment%`
- `FailedRetry::$isRetryable` ⟶ `%bro_world_core.messenger.failed_retry.is_retryable%`
- `FailedRetry::$retryWaitingTime` ⟶ `%bro_world_core.messenger.failed_retry.waiting_time%`
- `ApiProxyService::$baseUrls` ⟶ `%bro_world_core.api_proxy.base_urls%`
- `ApiProxyService::$uploadDefaults` ⟶ `%bro_world_core.api_proxy.upload_defaults%`

If you need to override any of those in a particular app, you can redefine the service in that app’s `services.yaml`.

---

## Messenger Integration (optional)

If you want to use the provided retry strategy:

```yaml
# config/packages/messenger.yaml
framework:
  messenger:
    default_bus: command.bus

    transports:
      async: '%env(MESSENGER_TRANSPORT_DSN)%'
      failed: '%env(MESSENGER_TRANSPORT_DSN_FAILED)%'

    routing:
      'Bro\\WorldCoreBundle\\Message\\': async

    # Attach custom retry strategy to a transport when desired
    transports_options:
      failed:
        retry_strategy:
          service: 'Bro\\WorldCoreBundle\\Infrastructure\\Messenger\\Strategy\\FailedRetry'
```

> Set `bro_world_core.messenger.failed_retry.*` options in `bro_world_core.yaml` to control behavior.

---

## Doctrine / Ramsey UUID (optional)

If your app uses Ramsey UUID and the DBAL types, install:

```bash
composer require ramsey/uuid ramsey/uuid-doctrine
```

The bundle can **prepend** a DBAL type mapping when Doctrine is present:

```php
// Pseudocode inside the bundle (guarded):
if ($builder->hasExtension('doctrine') && class_exists(\Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType::class)) {
    $container->extension('doctrine', [
        'dbal' => [
            'types' => [
                'uuid_binary_ordered_time' => \Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType::class,
            ],
        ],
    ]);
}
```

If you prefer, you can configure DBAL types manually in your app instead.

---

## Usage Examples

### Secured path regex
```yaml
# config/packages/bro_world_core.yaml
bro_world_core:
  security:
    secured_path_regex: '^/api/(?!.*(security)|(test)|(doc)).*$'
```

### Custom retry strategy for failed messages
```yaml
# config/packages/bro_world_core.yaml
bro_world_core:
  messenger:
    failed_retry:
      is_retryable: true
      waiting_time: 5000  # 5s
```

### Configuring the API proxy
```yaml
# config/packages/bro_world_core.yaml
bro_world_core:
  api_proxy:
    base_urls:
      media: 'https://media.example.tld/api'
      catalog: 'https://catalog.example.tld'
    upload_defaults:
      context_key_field: 'contextKey'
      context_id: '%env(default::MEDIA_CONTEXT_ID)%'
      media_folder: 'default'
      private: false
      headers:
        X-Source: 'bro-world-core'
```

```php
use Bro\WorldCoreBundle\Infrastructure\Service\ApiProxyService;
use Symfony\Component\HttpFoundation\Request;

final class ExampleUploader
{
    public function __construct(private ApiProxyService $proxy) {}

    public function __invoke(Request $request): array
    {
        return $this->proxy->requestFile('POST', 'media', $request, [
            'contextKey' => 'product-images',
            'mediaFolder' => 'product-images',
        ]);
    }
}
```

### Injecting the Clock service
```php
use Bro\WorldCoreBundle\Infrastructure\Service\Clock;

final class ExampleController
{
    public function __construct(private Clock $clock) {}

    public function __invoke(): string
    {
        return $this->clock->now()->format(DATE_ATOM);
    }
}
```

---

## Versioning & Releases

- Tags follow **SemVer** (e.g. `v0.1.0`, `v0.2.0`).
- When using `dev-master`, consider setting Composer’s `minimum-stability` to `dev` with `prefer-stable: true`, or define a branch alias in the bundle.

---

## Contributing

Issues and PRs are welcome.
- Issues: https://github.com/rami-aouinti/bro-world-core/issues
- Please keep additions **generic** and **microservice‑agnostic**.

---

## Security

If you discover a security vulnerability, please open a private issue or contact the maintainer directly.

---

## License

See the `LICENSE` file distributed with this repository. (Composer currently advertises `proprietary`.)

