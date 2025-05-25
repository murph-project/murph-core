<?php

namespace App\Core\BuilderBlock\Block\Editor;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

abstract class EditorBlock extends BuilderBlock
{
    public function configure()
    {
        $this->setCategory('Editors');
    }
}
