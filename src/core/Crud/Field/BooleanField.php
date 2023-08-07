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
            'default_value' => false,
        ]);

        return $resolver;
    }
}
