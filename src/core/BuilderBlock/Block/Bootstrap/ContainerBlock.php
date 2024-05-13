<?php

namespace App\Core\BuilderBlock\Block\Bootstrap;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class ContainerBlock extends BootstrapBlock
{
    public function configure()
    {
        parent::configure();

        $this
            ->setName('bsContainer')
            ->setLabel('Container')
            ->setIsContainer(true)
            ->setOrder(2)
            ->setTemplate('@Core/builder_block/bootstrap/container.html.twig')
            ->setIcon('<i class="fas fa-th"></i>')
            ->addSetting(name: 'isFluid', label: 'Fluid', type: 'checkbox')
        ;
    }
}
