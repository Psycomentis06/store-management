<?php

namespace App\Form;

use App\Entity\WorkEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Holiday' => 0,
                    'Summit' => 1,
                    'Urgency' => 2,
                ]
            ])
            ->add('fromDate', DateTimeType::class)
            ->add('toDate', DateTimeType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkEvent::class,
        ]);
    }
}
