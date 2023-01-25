<?php

namespace App\Core\Form\Site\Page;

use App\Core\Form\Type\TinymceTextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TinymceTextareaBlockType extends TextareaBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            TinymceTextareaType::class,
            array_merge([
                'required' => false,
                'label' => false,
            ], $options['options']),
        );
    }
}
