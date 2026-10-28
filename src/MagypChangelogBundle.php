<?php

namespace Magyp\ChangelogBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MagypChangelogBundle extends AbstractBundle {
    public function getPath(): string {
        return \dirname(__DIR__);
    }

    public function configure(DefinitionConfigurator $definition): void {
        $definition->rootNode()
            ->children()
                ->scalarNode('changelog_path')
                    ->defaultValue('%kernel.project_dir%/CHANGELOG.md')
                ->end()
                ->scalarNode('issue_url_pattern')
                    ->defaultNull()
                    ->info('URL con %d donde va el número de tarea, p.ej. https://redmine.example/issues/%d')
                ->end()
                ->scalarNode('layout')
                    ->defaultValue('base.html.twig')
                ->end()
                ->booleanNode('show_internal')
                    ->defaultFalse()
                ->end()
                ->scalarNode('route_path')
                    ->defaultValue('/versionado')
                ->end()
                ->scalarNode('twig_global_name')
                    ->defaultValue('app_version')
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void {
        $container->parameters()
            ->set('magyp.changelog.changelog_path', $config['changelog_path'])
            ->set('magyp.changelog.issue_url_pattern', $config['issue_url_pattern'])
            ->set('magyp.changelog.layout', $config['layout'])
            ->set('magyp.changelog.show_internal', $config['show_internal'])
            ->set('magyp.changelog.route_path', $config['route_path'])
            ->set('magyp.changelog.twig_global_name', $config['twig_global_name'])
        ;

        $container->import('../config/services.yaml');
    }
}
