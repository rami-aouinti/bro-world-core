<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\DefinitionConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class BroWorldCoreBundle extends AbstractBundle
{
    /**
     * Déclare l'arbre de configuration du bundle.
     * Clé de config: "bro_world_core" (cf. fichier config/packages/bro_world_core.yaml)
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->scalarNode('default_locale')->defaultValue('fr')->end()
            ->booleanNode('enable_feature_x')->defaultFalse()->end()
            ->end();
    }


    /**
     * Charge la configuration des services du bundle et propage la config dans des paramètres.
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/Resources/config/services.php');
        $container->parameters()->set('bro_world_core.default_locale', $config['default_locale']);
        $container->parameters()->set('bro_world_core.enable_feature_x', $config['enable_feature_x']);
    }
}