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
            ->scalarNode('secured_path_regex')
            ->defaultValue('^/api/(?!.*(security)|(test)|(doc)).*$')
            ->end()
            ->end()
            ->end()

            ->arrayNode('messenger')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('failed_retry')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('is_retryable')->defaultFalse()->end()
            ->integerNode('waiting_time')->min(0)->defaultValue(0)->end()
            ->end()
            ->end()
            ->end()
            ->end()

            ->arrayNode('media')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('api_base_url')->defaultValue('')->end()
            ->end()
            ->end()

            ->arrayNode('api_proxy')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('base_urls')
            ->useAttributeAsKey('type')
            ->scalarPrototype()->defaultValue('')->end()
            ->defaultValue(['media' => ''])
            ->end()
            ->arrayNode('upload_defaults')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('context_key_field')->defaultValue('contextKey')->end()
            ->scalarNode('context_value')->defaultNull()->end()
            ->scalarNode('context_id')->defaultNull()->end()
            ->scalarNode('workplace_id')->defaultNull()->end()
            ->scalarNode('media_folder')->defaultNull()->end()
            ->booleanNode('private')->defaultTrue()->end()
            ->scalarNode('files_parameter')->defaultValue('files')->end()
            ->arrayNode('extra_fields')
            ->normalizeKeys(false)
            ->scalarPrototype()->end()
            ->defaultValue([])
            ->end()
            ->arrayNode('headers')
            ->normalizeKeys(false)
            ->scalarPrototype()->end()
            ->defaultValue([])
            ->end()
            ->end()
            ->end()

            ->arrayNode('elasticsearch')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('host')->defaultValue('')->end()
            ->scalarNode('username')->defaultValue('')->end()
            ->scalarNode('password')->defaultValue('')->end()
            ->end()
            ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/Resources/config/services.php');

        // exposed params
        $container->parameters()->set('bro_world_core.default_locale', $config['default_locale'] ?? 'fr');
        $container->parameters()->set('bro_world_core.enable_feature_x', $config['enable_feature_x'] ?? false);
        $container->parameters()->set('bro_world_core.jwt_public_key', $config['jwt_public_key'] ?? null);

        $container->parameters()->set(
            'bro_world_core.security.secured_path_regex',
            $config['security']['secured_path_regex'] ?? '^/api/(?!.*(security)|(test)|(doc)).*$'
        );

        $container->parameters()->set(
            'bro_world_core.messenger.failed_retry.is_retryable',
            $config['messenger']['failed_retry']['is_retryable'] ?? false
        );
        $container->parameters()->set(
            'bro_world_core.messenger.failed_retry.waiting_time',
            $config['messenger']['failed_retry']['waiting_time'] ?? 0
        );

        $container->parameters()->set(
            'bro_world_core.media.api_base_url',
            $config['media']['api_base_url'] ?? ''
        );

        $baseUrls = $config['api_proxy']['base_urls'] ?? [];

        if (empty($baseUrls['media']) && !empty($config['media']['api_base_url'])) {
            $baseUrls['media'] = $config['media']['api_base_url'];
        }

        $container->parameters()->set('bro_world_core.api_proxy.base_urls', $baseUrls);
        $container->parameters()->set(
            'bro_world_core.api_proxy.upload_defaults',
            $config['api_proxy']['upload_defaults'] ?? []
        );

        $container->parameters()->set(
            'bro_world_core.elasticsearch.host',
            $config['elasticsearch']['host'] ?? ''
        );
        $container->parameters()->set(
            'bro_world_core.elasticsearch.username',
            $config['elasticsearch']['username'] ?? ''
        );
        $container->parameters()->set(
            'bro_world_core.elasticsearch.password',
            $config['elasticsearch']['password'] ?? ''
        );

    }
}
