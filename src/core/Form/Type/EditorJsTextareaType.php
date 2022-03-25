<?php

namespace App\Core\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class EditorJsTextareaType extends TextareaType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['attr']['data-editorjs'])) {
            $view->vars['attr']['data-editorjs'] = '';
        }

        return parent::buildView($view, $form, $options);
    }
}
