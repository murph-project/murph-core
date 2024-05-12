<?php

namespace App\Core\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use App\Core\BuilderBlock\BuilderBlockContainer;

class BuilderBlockPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(BuilderBlockContainer::class)) {
            return;
        }

        $definition = $container->findDefinition(BuilderBlockContainer::class);
        $taggedServices = $container->findTaggedServiceIds('builder_block.widget');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addWidget', [new Reference($id)]);
        }
    }
}
