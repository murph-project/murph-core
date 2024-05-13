<?php

namespace App\Core\BuilderBlock\Block\Bootstrap;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class ColumnBlock extends BootstrapBlock
{
    public function configure()
    {
        parent::configure();

        $this
            ->setName('bsColumn')
            ->setLabel('Column')
            ->setIsContainer(true)
            ->setOrder(3)
            ->setClass('col-12 col-lg-2 pr-md-1')
            ->setTemplate('@Core/builder_block/bootstrap/column.html.twig')
            ->setIcon('<i class="fas fa-columns"></i>')
            ->addSetting(name: 'size', label: 'Extra small', type: 'number', attributes: ['min' => 0, 'max' => 12])
            ->addSetting(name: 'sizeSm', label: 'Small', type: 'number', attributes: ['min' => 0, 'max' => 12])
            ->addSetting(name: 'sizeMd', label: 'Medium', type: 'number', attributes: ['min' => 0, 'max' => 12])
            ->addSetting(name: 'sizeLg', label: 'Large', type: 'number', attributes: ['min' => 0, 'max' => 12])
            ->addSetting(name: 'sizeXl', label: 'Extra large', type: 'number', attributes: ['min' => 0, 'max' => 12])
        ;
    }
}
