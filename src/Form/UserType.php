<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('email')
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'role',
            ])
            ->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($roles) {
                        $res = [];
                        foreach ($roles as $roleName) {
                            $role = $this->roleRepository->findOneBy(['role' => $roleName]);
                            if (!empty($role))
                                $res[] = $role;
                        }
                        return $res;
                    },
                    function ($roles) {
                        return $roles;
                    })
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
