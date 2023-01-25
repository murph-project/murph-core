<?php

namespace App\Core\Form\Site\Page;

use App\Core\Form\Type\EditorJsTextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class EditorJsTextareaBlockType extends TextareaBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            EditorJsTextareaType::class,
            array_merge([
                'required' => false,
                'label' => false,
            ], $options['options']),
        );
    }
}
