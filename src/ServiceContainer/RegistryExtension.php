<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace eBayEnterprise\Behat\RegistryExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegistryExtension
{
    /**
     * Registry ID in service container.
     */
    const REGISTRY_ID = 'registry';

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadRegistry($container, $config);
        $this->loadContextInitializer($container);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('persister')->defaultNull()->end()
            ->end();
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return Definition
     */
    private function loadRegistry(ContainerBuilder $container, array $config)
    {
        $registry = new Definition('eBayEnterprise\Behat\RegistryExtension\Registry');

        $persister = $config['persister'];
        if ($persister !== null) {
            $registry->addMethodCall('setPersister', array($this->resolvePersisterDefinition($persister)));
        }

        $container->setDefinition(self::REGISTRY_ID, $registry);
    }

    /**
     * @param string $name
     *
     * @return Reference
     */
    private function resolvePersisterDefinition($name)
    {
        if ($name && $name{0} == '@') {
            return new Reference(substr($name, 1));
        }

        return new Definition($name);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function loadContextInitializer(ContainerBuilder $container)
    {
        $definition = new Definition(
            '\eBayEnterprise\Behat\RegistryExtension\Context\Initializer\RegistryAwareInitializer',
            array(new Reference(self::REGISTRY_ID))
        );

        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));

        $container->setDefinition('registry.context_initializer', $definition);
    }
}
