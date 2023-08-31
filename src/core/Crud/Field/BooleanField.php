<?php

namespace App\Core\Crud\Field;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * class BooleanField.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class BooleanField extends Field
{
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'view' => '@Core/admin/crud/field/boolean.html.twig',
            'display' => 'toggle',
            'checkbox_class_when_true' => 'fa-check-square',
            'checkbox_class_when_false' => 'fa-square',
            'toggle_class_when_true' => 'bg-success',
            'toggle_class_when_false' => 'bg-secondary',
            'default_value' => false,
        ]);

        $resolver->setAllowedTypes('display', 'string');

        return $resolver;
    }
}
