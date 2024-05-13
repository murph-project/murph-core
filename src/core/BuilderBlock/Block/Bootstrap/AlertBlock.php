<?php

namespace App\Core\BuilderBlock\Block\Bootstrap;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AutoconfigureTag('builder_block.widget')]
class AlertBlock extends BootstrapBlock
{
    public function __construct(protected TranslatorInterface $translator)
    {
    }

    public function configure()
    {
        parent::configure();

        $options = [];

        foreach ([
            'Primary' => 'primary',
            'Secondary' => 'secondary',
            'Info' => 'info',
            'Success' => 'success',
            'Danger' => 'danger',
            'Warning' => 'warning',
            'Light' => 'light',
            'Dark' => 'dark',
        ] as $k => $v) {
            $options[] = [
                'text' => $this->translator->trans($k),
                'value' => $v,
            ];
        }

        $this
            ->setName('bsAlert')
            ->setLabel('Alert')
            ->setOrder(4)
            ->setIsContainer(true)
            ->setIcon('<i class="fas fa-exclamation-circle"></i>')
            ->setTemplate('@Core/builder_block/bootstrap/alert.html.twig')
            ->addSetting(name: 'level', label: 'Level', type: 'select', extraOptions: ['options' => $options])
        ;
    }
}
