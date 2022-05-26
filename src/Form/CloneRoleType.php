<?php

namespace App\Form;

use App\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CloneRoleType extends AbstractType
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {

        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $this->roleRepository->findAll();

        $builder
            ->add('role', TextType::class, [
                'constraints' => [
                    new NotBlank(message: "Field empty"),
                    new Length(min: 3, max: 25, minMessage: "Role name length lower than 3", maxMessage: "Role name length greater than 25")
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => false,
                'choices' => $roles,
                'choice_label' => 'role'
            ]);
    }
}