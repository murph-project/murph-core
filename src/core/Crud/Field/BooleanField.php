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
            'default_value' => false,
        ]);

        $resolver->setAllowedTypes('display', 'string');

        return $resolver;
    }
}
