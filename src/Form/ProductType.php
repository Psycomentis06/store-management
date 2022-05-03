<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttribute('id', 'abc')
            ->add('name')
            ->add('description')
            ->add('sku')
            ->add('discount')
            ->add('guarantee')
            ->add('properties',
                CollectionType::class,
                [
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => JsonKeyValueType::class,
                    'attr' => [
                        'class' => 'json_key_value_form'
                    ]
                ])
            ->add('digital')
            ->add('images', SingleFilePicker::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
