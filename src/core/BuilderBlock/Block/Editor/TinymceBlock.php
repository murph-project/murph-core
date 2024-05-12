<?php

namespace App\Core\BuilderBlock\Block\Editor;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class TinymceBlock extends EditorBlock
{
    public function configure()
    {
        parent::configure();

        $this
            ->setName('tinymce')
            ->setLabel('TinyMCE')
            ->setIsContainer(false)
            ->setIcon('<i class="fas fa-pencil-alt"></i>')
            ->setTemplate('@Core/builder_block/editor/tinymce.html.twig')
            ->addSetting(name: 'value', type: 'textarea', attributes: ['data-tinymce' => ''])
        ;
    }
}
