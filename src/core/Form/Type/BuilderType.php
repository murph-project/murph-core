<?php

namespace App\Core\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType as BaseCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BuilderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_replace($view->vars, [
            'allowed_widgets' => $options['allowed_widgets'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'compound' => false,
            'allowed_widgets' => [],
        ]);

        $resolver->setAllowedTypes('allowed_widgets', 'array');
    }

    public function getBlockPrefix()
    {
        return 'builder';
    }
}
