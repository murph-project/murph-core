<?php

namespace App\Core\BuilderBlock\Block\Bootstrap;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class RowBlock extends BootstrapBlock
{
    public function configure()
    {
        parent::configure();

        $this
            ->setName('bsRow')
            ->setLabel('Row')
            ->setOrder(2)
            ->setIsContainer(true)
            ->setIcon('<i class="fas fa-align-justify"></i>')
            ->setTemplate('@Core/builder_block/bootstrap/row.html.twig')
            ->addWidget('bsColumn')
        ;
    }
}
