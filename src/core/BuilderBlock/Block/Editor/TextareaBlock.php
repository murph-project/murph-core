<?php

namespace App\Core\BuilderBlock\Block\Editor;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class TextareaBlock extends EditorBlock
{
    public function configure()
    {
        parent::configure();

        $this
            ->setName('textarea')
            ->setLabel('Text')
            ->setIsContainer(false)
            ->setIcon('<i class="fas fa-pencil-alt"></i>')
            ->setTemplate('@Core/builder_block/editor/textarea.html.twig')
            ->addSetting(name: 'nl2br', label: 'Insert line breaks', type: 'checkbox', default: true)
            ->addSetting(name: 'allowHtml', label: 'Allow HTML', type: 'checkbox', default: false)
            ->addSetting(name: 'value', type: 'textarea')
            ->setPreview('value')
        ;
    }
}
