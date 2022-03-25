<?php

namespace App\Core\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TinymceTextareaType extends TextareaType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['attr']['data-tinymce'])) {
            $view->vars['attr']['data-tinymce'] = '';
        }

        return parent::buildView($view, $form, $options);
    }
}
