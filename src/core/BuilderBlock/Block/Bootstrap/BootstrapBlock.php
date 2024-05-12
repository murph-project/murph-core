<?php

namespace App\Core\BuilderBlock\Block\Bootstrap;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

class BootstrapBlock extends BuilderBlock
{
    public function configure()
    {
        $this->setCategory('Bootstrap');
    }
}
