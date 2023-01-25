<?php

namespace App\Core\Form\Site\Page;

use App\Core\Form\Type\GrapesJsType;
use Symfony\Component\Form\FormBuilderInterface;

class GrapesJsBlockType extends TextareaBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            GrapesJsType::class,
            array_merge([
                'required' => false,
                'label' => false,
            ], $options['options']),
        );
    }
}
