<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class SingleFilePicker extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'resources',
                HiddenType::class, [
                    'attr' => [
                        'id' => 'singleFilePickerInput'
                    ]
            ]);
    }
}