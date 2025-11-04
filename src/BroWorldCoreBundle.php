<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator; // <-- CORRECT
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
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/Resources/config/services.php');

        $container->parameters()->set('bro_world_core.default_locale', $config['default_locale'] ?? 'fr');
        $container->parameters()->set('bro_world_core.enable_feature_x', $config['enable_feature_x'] ?? false);
        $container->parameters()->set('bro_world_core.jwt_public_key', $config['jwt_public_key'] ?? null);
    }
}
