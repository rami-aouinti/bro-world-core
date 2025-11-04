<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class BroWorldCoreBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->scalarNode('default_locale')->defaultValue('fr')->end()
            ->booleanNode('enable_feature_x')->defaultFalse()->end()
            ->scalarNode('jwt_public_key')->defaultNull()->end()
            ->arrayNode('security')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('secured_path_regex')->defaultValue('^/api/(?!.*(security)|(test)|(doc)).*$')->end()
            ->end()
            ->end()
            ->children()
            ->arrayNode('messenger')->addDefaultsIfNotSet()->children()
            ->arrayNode('failed_retry')->addDefaultsIfNotSet()->children()
            ->booleanNode('is_retryable')->defaultFalse()->end()
            ->integerNode('waiting_time')->min(0)->defaultValue(0)->end()
            ->end()->end()
            ->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__.'/Resources/config/services.php');

        // paramètres exposés
        $container->parameters()->set('bro_world_core.default_locale', $config['default_locale'] ?? 'fr');
        $container->parameters()->set('bro_world_core.enable_feature_x', $config['enable_feature_x'] ?? false);
        $container->parameters()->set('bro_world_core.jwt_public_key', $config['jwt_public_key'] ?? null);
        $container->parameters()->set(
            'bro_world_core.security.secured_path_regex',
            $config['security']['secured_path_regex'] ?? '^/api/(?!.*(security)|(test)|(doc)).*$'
        );
        $container->parameters()->set('bro_world_core.messenger.failed_retry.is_retryable', $config['messenger']['failed_retry']['is_retryable'] ?? false);
        $container->parameters()->set('bro_world_core.messenger.failed_retry.waiting_time', $config['messenger']['failed_retry']['waiting_time'] ?? 0);
    }
}
