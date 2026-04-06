<?php

namespace App\Core\DependencyInjection;

use App\Core\DependencyInjection\Compiler\BuilderBlockPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class CoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('core', $config);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $configs, ContainerBuilder $container): ?\Symfony\Component\Config\Definition\ConfigurationInterface
    {
        return new Configuration();
    }
}
