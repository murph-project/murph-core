<?php

namespace App\Core\Form\Site\Page;

use App\Core\Entity\Site\Page\BuilderBlock;
use App\Core\Form\Type\BuilderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuilderBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            BuilderType::class,
            array_merge([
                'required' => false,
                'label' => false,
            ], $options['options']),
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BuilderBlock::class,
            'options' => [],
        ]);
    }
}
