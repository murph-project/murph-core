<?php

namespace App\Core\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class GrapesJsType extends TextareaType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['attr']['data-grapesjs'])) {
            $view->vars['attr']['data-grapesjs'] = '';
        }

        return parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'grapesjs';
    }
}
