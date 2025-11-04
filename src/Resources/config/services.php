<?php

declare(strict_types=1);

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
            dirname(__DIR__, 2) . '/Tests',
        ]);

};