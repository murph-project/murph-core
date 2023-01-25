<?php

namespace App\Core\Form\Site\Page;

use App\Core\Entity\Site\Page\FileBlock;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileBlockType extends TextBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            FileType::class,
            array_merge([
                'required' => false,
                'label' => false,
            ], $options['options']),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'file_type' => $options['file_type'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileBlock::class,
            'file_type' => 'auto',
            'options' => [],
        ]);
    }
}
