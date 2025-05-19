<?php

namespace App\Core\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType as BaseCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends BaseCollectionType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $classes = [
            'add_attr' => 'collection-add',
            'delete_attr' => 'text-right',
        ];

        foreach ($classes as $key => $class) {
            $options[$key]['class'] = $class.' '.($options[$key]['class'] ?? '');
        }

        $view->vars = array_replace($view->vars, [
            'collection_name' => $options['collection_name'],
            'label_add' => $options['label_add'],
            'label_delete' => $options['label_delete'],
            'allow_add' => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
            'add_attr' => $options['add_attr'],
            'delete_attr' => $options['delete_attr'],
            'template_before_item' => $options['template_before_item'],
            'template_after_item' => $options['template_after_item'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'collection_name' => '',
            'label_add' => 'Add',
            'label_delete' => 'Delete',
            'template_before_item' => null,
            'template_after_item' => null,
            'add_attr' => [],
            'delete_attr' => [],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'murph_collection';
    }
}
