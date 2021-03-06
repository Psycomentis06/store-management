<?php

namespace App\Form;

use App\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address', CollectionType::class, [
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => JsonKeyValueType::class,
                'attr' => [
                    'class' => 'json_key_value_form'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Store::class,
        ]);
    }
}
