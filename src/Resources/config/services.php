<?php

declare(strict_types=1);

use Bro\WorldCoreBundle\Infrastructure\Service\LexikJwtAuthenticatorService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Bro\WorldCoreBundle\Transport\EventSubscriber\ExceptionSubscriber;
use Bro\WorldCoreBundle\Infrastructure\Messenger\Strategy\FailedRetry;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    /** @var ServicesConfigurator $services */
    $services = $container->services();


    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Bro\\WorldCoreBundle\\', dirname(__DIR__, 2) . '/*')
        ->exclude([
            dirname(__DIR__, 2) . '/Resources',
            dirname(__DIR__, 2) . '/Tests',
        ]);
    $services->set(ExceptionSubscriber::class)
        ->arg('$environment', '%kernel.environment%');
    $services->set(LexikJwtAuthenticatorService::class)
        ->arg('$path', '%bro_world_core.security.secured_path_regex%');
    $services->set(FailedRetry::class)
        ->arg('$isRetryable', '%bro_world_core.messenger.failed_retry.is_retryable%')
        ->arg('$retryWaitingTime', '%bro_world_core.messenger.failed_retry.waiting_time%');
};