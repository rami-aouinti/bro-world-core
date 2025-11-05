<?php

declare(strict_types=1);

use Bro\WorldCoreBundle\Infrastructure\Messenger\Strategy\FailedRetry;
use Bro\WorldCoreBundle\Infrastructure\Service\ApiProxyService;
use Bro\WorldCoreBundle\Infrastructure\Service\ElasticsearchService;
use Bro\WorldCoreBundle\Infrastructure\Service\LexikJwtAuthenticatorService;
use Bro\WorldCoreBundle\Transport\EventSubscriber\ExceptionSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;

return static function (ContainerConfigurator $container): void {
    /** @var ServicesConfigurator $services */
    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Bro\\WorldCoreBundle\\', dirname(__DIR__, 2) . '/*')
        ->exclude([
            dirname(__DIR__, 2) . '/Resources',
        ]);

    $services->set(ExceptionSubscriber::class)
        ->arg('$environment', '%kernel.environment%');

    $services->set(LexikJwtAuthenticatorService::class)
        ->arg('$path', '%bro_world_core.security.secured_path_regex%');

    $services->set(FailedRetry::class)
        ->arg('$isRetryable', '%bro_world_core.messenger.failed_retry.is_retryable%')
        ->arg('$retryWaitingTime', '%bro_world_core.messenger.failed_retry.waiting_time%');

    $services->set(ApiProxyService::class)
        ->arg('$apiMediaBaseUrl', '%bro_world_core.media.api_base_url%');

    $services->set(ElasticsearchService::class)
        ->arg('$host', '%bro_world_core.elasticsearch.host%')
        ->arg('$username', '%bro_world_core.elasticsearch.username%')
        ->arg('$password', '%bro_world_core.elasticsearch.password%');
};
